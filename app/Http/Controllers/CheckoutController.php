<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session; // Para manejar el carrito

class CheckoutController extends Controller
{
    /**
     * Muestra la página de checkout.
     */
    public function index()
    {
        $cart = Session::get('cart', []);

        // Si el carrito está vacío, no se reealiza checkout.
        if (empty($cart)) {
            // Redirigimos al catálogo 
            return redirect()->route('catalog.index')->with('error', 'Tu carrito está vacío para proceder al pago.');
        }

        // Calculo del total para mostrarlo 
        $total = 0;
        foreach ($cart as $id => $details) {
            // Asegurarse que price y quantity existen y son numéricos
            $price = $details['price'] ?? 0;
            $quantity = $details['quantity'] ?? 0;
            if(is_numeric($price) && is_numeric($quantity)){
               $total += $price * $quantity;
            } else {

                Session::forget("cart.$id"); // Quitamos el item inválido
                 return redirect()->route('cart.index')->with('error', 'Se encontró un item inválido en tu carrito y fue eliminado. Por favor, revisa tu carrito.');
            }
        }

        //  después de limpiar items inválidos el carrito queda vacío
        if(empty(Session::get('cart', []))){
             return redirect()->route('catalog.index')->with('error', 'Tu carrito está vacío.');
        }


        return view('checkout.index', compact('cart', 'total'));
    }

    /**
     * Proceso el pedido.
     */
    public function store(Request $request)
    {
        // ---  VALIDACIÓN DE DATOS ---
        $request->validate([
            'direccion_envio' => 'required|string|max:500',
            'metodo_pago' => 'required|in:efectivo,transferencia',
            'monto_efectivo' => 'nullable|required_if:metodo_pago,efectivo|numeric|min:0',
            'referencia_pago' => 'nullable|required_if:metodo_pago,transferencia|string|max:255',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('catalog.index')->with('error', 'Tu carrito está vacío.');
        }

        // ---  INICIO DE LA TRANSACCIÓN ---
        DB::beginTransaction();

        try {
            // --- 3. VERIFICAR STOCK Y CALCULAR TOTAL ---
            $totalPedido = 0;
            $itemsParaBaseDatos = [];
            $productosParaActualizarStock = []; // Guardar productos para descontar stock al final

            foreach ($cart as $id => $details) {
                // evitar problemas de concurrencia
                $product = Product::lockForUpdate()->find($id);

                if (!$product) {
                    throw new \Exception("El producto '".($details['name'] ?? 'ID:'.$id)."' ya no está disponible.");
                }

                $cantidadPedida = floatval($details['quantity'] ?? 0);
                // Validar que la cantidad sea positiva
                if ($cantidadPedida <= 0) {
                     throw new \Exception("La cantidad para '".$product->nombre."' debe ser mayor a cero.");
                }

                // Validar stock disponible
                if (floatval($product->stock) < $cantidadPedida) {
                    throw new \Exception("Stock insuficiente para '{$product->nombre}'. Disponible: {$product->stock} Kg");
                }

                // Usar el precio de la base de datos (más seguro)
                $subtotal = $product->precio * $cantidadPedida;
                $totalPedido += $subtotal;

                // Preparar datos para guardar en order_items
                $itemsParaBaseDatos[] = [
                    'product_id' => $id,
                    'cantidad' => $cantidadPedida,
                    'precio_unitario' => $product->precio, // Precio de la BD
                ];

                // Guardar referencia al producto y cantidad para descontar stock
                 $productosParaActualizarStock[] = [
                    'product' => $product,
                    'cantidad_a_restar' => $cantidadPedida
                ];
            }

            // --- VALIDAR PAGO EN EFECTIVO ---
            if ($request->metodo_pago == 'efectivo') {
                // Asegurarse que monto_efectivo no sea null 
                if($request->monto_efectivo === null){
                     throw new \Exception("Debes indicar con cuánto vas a pagar en efectivo.");
                }
                $montoRecibido = (float) $request->monto_efectivo;
                // Validar que el monto recibido sea suficiente
                if ($montoRecibido < $totalPedido) {
                    throw new \Exception("El monto de pago en efectivo ($".number_format($montoRecibido).") es menor al total del pedido ($".number_format($totalPedido).").");
                }
            }

            // --- . CREAR EL PEDIDO (ORDER) ---
            $order = Order::create([
                'user_id' => Auth::id(),
                'direccion_envio' => $request->direccion_envio,
                'total' => $totalPedido, // Usar el total calculado 
                'metodo_pago' => $request->metodo_pago,
                'referencia_pago' => $request->metodo_pago == 'transferencia' ? $request->referencia_pago : null,
                'monto_efectivo' => $request->metodo_pago == 'efectivo' ? $request->monto_efectivo : null,
                'estado_pedido' => 'pendiente despacho', // Estado inicial del pedido
                'estado_pago' => $request->metodo_pago == 'transferencia' ? 'pagada' : 'pendiente', // Estado inicial del pago
            ]);

            // ---  CREAR LOS ITEMS DEL PEDIDO (ORDER_ITEMS) ---
            foreach ($itemsParaBaseDatos as $item) {
                //  asignar automáticamente el order_id
                $order->items()->create($item);
            }

            // --- DESCONTAR EL STOCK ---
            // Hacemos esto después de crear los items por si algo fallara antes
            foreach($productosParaActualizarStock as $stockUpdate){
                // manejo de decimales
                $stockUpdate['product']->decrement('stock', $stockUpdate['cantidad_a_restar']);
            }


            // ---  CONFIRMAR LA TRANSACCIÓN ---
            DB::commit();

            // ---  VACIAR EL CARRITO Y REDIRIGIR ---
            Session::forget('cart');

        
            // , redirigimos al catálogo con mensaje de éxito
            return redirect()->route('catalog.index')->with('success', '¡Tu pedido #'.$order->id.' ha sido recibido! Lo prepararemos pronto.');

        } catch (\Exception $e) {
            // --- REVERTIR TODO SI ALGO FALLÓ ---
            DB::rollBack();
            // Devolvemos al usuario a la página de checkout con el error específico
            // U withInput() para no perder los datos escritos en el formulario
            return redirect()->route('checkout.index')->withInput()->with('error', 'Error al procesar pedido: '.$e->getMessage());
        }
    }
}

