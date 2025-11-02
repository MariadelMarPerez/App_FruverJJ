<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail; 
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'details.product'])->orderBy('created_at', 'desc');

        // --- FILTROS ---
        if ($request->filled('product_search')) {
            $searchTerm = $request->product_search;
            // Búsqueda
            $query->whereHas('details.product', function ($q) use ($searchTerm) {
                // Usamos LOWER() para forzar la comparación en minúsculas
                $q->where(DB::raw('LOWER(nombre)'), 'like', '%' . strtolower($searchTerm) . '%');
            });
        }

        
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        

        // =========>  LA PAGINACIÓN <=========
        $sales = $query->paginate(12)->withQueryString(); 
        //=======================================================

        // --- Cálculo de Totales para la Vista  ---
        // consulta ANTES de paginar para obtener los totales CORRECTOS sobre TODOS los resultados filtrados
        $queryForTotals = clone $query->getQuery();
        //el filtro de estado 'pagada'
        $queryForTotals->orders = null; // Quitar orderBy para el count/sum
        // Ejecutamos la consulta para los totales sin paginación
        $filteredSalesResults = $queryForTotals->where('estado', 'pagada')->get();
        // Convertimos los resultados a una colección de modelos Sale para usar sum() fácilmente
        $filteredSales = Sale::hydrate($filteredSalesResults->toArray());


        $totalIngresos = $filteredSales->sum('total');
        $totalVentas = $filteredSales->count();
        // Para sumar cantidades necesitamos cargar la relación 'details' en los resultados filtrados
        $productosVendidos = 0;
        if ($filteredSales->isNotEmpty()) {
            // Cargar 'details' solo para los resultados filtrados usados para totales
             $filteredSales->load('details'); 
             $productosVendidos = $filteredSales->sum(function($sale) {
                 return $sale->details->sum('cantidad');
             });
        }
        // --- Fin Cálculo de Totales ---


        return view('sales.index', compact('sales', 'totalVentas', 'totalIngresos', 'productosVendidos'));
    }

    // --- MÉTODOS CREATE, STORE, SHOW, DESTROY, PDF, ETC. ---
    

    public function create()
    {
        // Solo productos activos y con stock
        $products = Product::where('stock', '>', 0)
                            //->where('is_active', true) // Descomentar si tienes is_active en productos
                            ->orderBy('nombre')
                            ->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente' => 'nullable|string|max:255',
            'cart' => 'required|string', // El carrito vendrá como JSON
            'metodo_pago' => 'required|in:efectivo,transferencia',
            'monto_recibido' => 'nullable|required_if:metodo_pago,efectivo|numeric|min:0',
            'referencia_transferencia' => 'nullable|required_if:metodo_pago,transferencia|string|max:255',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart)) {
            return back()->with('error', 'El carrito está vacío.');
        }

        DB::beginTransaction();
        try {
            $totalVenta = 0;
            $productosParaActualizarStock = [];

            // 1. Recalcular total y verificar stock en el servidor
            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->find($item['id']); 
                if (!$product) {
                    throw new \Exception("Producto no encontrado ID: " . $item['id']);
                }
                 $requestedQuantity = floatval($item['quantity']);
                 $availableStock = floatval($product->stock);

                if ($availableStock < $requestedQuantity) {
                    throw new \Exception("Stock insuficiente para: " . $product->nombre . ". Disponible: " . $availableStock . " Kg");
                }
                
                 $productPrice = floatval($product->precio); 
                 $subtotal = $requestedQuantity * $productPrice; 
                $totalVenta += $subtotal;

                $productosParaActualizarStock[] = [
                    'product' => $product,
                    'quantity_sold' => $requestedQuantity
                ];
            }

            // 2. Validar pago en efectivo
            $cambio = null;
            if ($request->metodo_pago == 'efectivo') {
                 $montoRecibido = floatval($request->monto_recibido);
                if ($montoRecibido < $totalVenta) {
                    throw new \Exception("El monto recibido ($".number_format($montoRecibido,2).") es menor al total de la venta ($".number_format($totalVenta,2).").");
                }
                $cambio = $montoRecibido - $totalVenta;
            }

            // 3. Crear la Venta (Maestro)
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'cliente' => $request->cliente,
                'total' => $totalVenta,
                'estado' => 'pagada', 
                'metodo_pago' => $request->metodo_pago,
                'monto_recibido' => $request->metodo_pago == 'efectivo' ? $montoRecibido : null,
                'cambio' => $cambio,
                'referencia_transferencia' => $request->metodo_pago == 'transferencia' ? $request->referencia_transferencia : null,
            ]);

            // 4. Crear los Detalles de la Venta
            foreach ($cart as $index => $item) {
                 $product = $productosParaActualizarStock[$index]['product']; 
                 $requestedQuantity = $productosParaActualizarStock[$index]['quantity_sold'];
                 $productPrice = floatval($product->precio);
                 $subtotal = $requestedQuantity * $productPrice;
                 
                 SaleDetail::create([
                     'sale_id' => $sale->id,
                     'product_id' => $item['id'],
                     'cantidad' => $requestedQuantity, 
                     'precio_unitario' => $productPrice, 
                     'subtotal' => $subtotal,
                 ]);
            }

            // 5. Actualizar el Stock
            foreach ($productosParaActualizarStock as $stockUpdate) {
                //  maneja decimales correctamente
                $stockUpdate['product']->decrement('stock', $stockUpdate['quantity_sold']); 
            }

            DB::commit();

            $successMessage = 'Venta registrada exitosamente.';
            if ($cambio !== null && $cambio >= 0) { // Mostrar cambio incluso si es 0
                $successMessage .= ' Cambio a devolver: $' . number_format($cambio, 2);
            }

            return redirect()->route('sales.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al registrar venta: " . $e->getMessage() . " - Carrito: " . $request->cart);
            return back()->withInput()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale) 
    {
        $sale->load(['user', 'details.product']); // Cargar relaciones
        return view('sales.show', compact('sale'));
    }

    public function destroy(Sale $sale) 
    {
        if ($sale->estado != 'pagada') {
             return redirect()->route('sales.index')->with('error', 'Solo se pueden cancelar ventas pagadas.');
        }

        DB::beginTransaction();
        try {
            foreach ($sale->details as $detail) {
                if ($detail->product) {
                    //  maneja decimales correctamente
                    $detail->product->increment('stock', $detail->cantidad); 
                } else {
                    Log::warning("Intento de devolver stock a producto no existente ID: {$detail->product_id} desde Venta ID: {$sale->id}");
                }
            }
             $sale->update(['estado' => 'cancelada']); 
            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Venta cancelada exitosamente y stock devuelto.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al cancelar venta ID {$sale->id}: " . $e->getMessage());
            return redirect()->route('sales.index')->with('error', 'Error al cancelar la venta: ' . $e->getMessage());
        }
    }

    public function generatePDF(Request $request) // Recibe  para filtros
    {
         $query = Sale::with(['user', 'details.product'])->where('estado', 'pagada')->orderBy('created_at', 'desc');

        if ($request->filled('product_search')) {
            $searchTerm = $request->product_search;
            $query->whereHas('details.product', function ($q) use ($searchTerm) {
                 $q->where(DB::raw('LOWER(nombre)'), 'like', '%' . strtolower($searchTerm) . '%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from); 
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $sales = $query->get(); //  TODOS los resultados filtrados
        
        $totalVentas = $sales->count();
        $totalIngresos = $sales->sum('total');
        $productosVendidos = 0;
         // Cargar detalles para sumar productos vendidos
         $sales->load('details'); 
         $productosVendidos = $sales->sum(function($sale) {
             return $sale->details->sum('cantidad');
         });

        $pdf = Pdf::loadView('sales.pdf', compact('sales', 'totalVentas', 'totalIngresos', 'productosVendidos'));
        
        $filename = 'reporte-ventas-' . date('Y-m-d');
        if($request->filled('date_from')) $filename .= '-desde-'.$request->date_from;
        if($request->filled('date_to')) $filename .= '-hasta-'.$request->date_to;
        $filename .= '.pdf';

        return $pdf->download($filename);
    }
    
     public function generateSalePDF(Sale $sale) 
     {
         $sale->load(['user', 'details.product']);
         $pdf = Pdf::loadView('sales.pdf_single', compact('sale')); 
         return $pdf->download('venta-' . $sale->id . '.pdf');
     }

    // Edit y Update siguen deshabilitados intencionalmente
    public function edit(Sale $sale) { abort(404); }
    public function update(Request $request, Sale $sale) { abort(404); }
}
