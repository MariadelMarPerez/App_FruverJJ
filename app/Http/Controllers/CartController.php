<?php

namespace App\Http\Controllers;

use App\Models\Product; // Importar Productos
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // manejo la sesión del carrito

class CartController extends Controller
{
    /**
     * Muestra la página del carrito de compras.
     * Tarea 2 (index) del Paso 4.
     */
    public function index()
    {
        $cart = Session::get('cart', []); // Obtiene el carrito de la sesión
        return view('cart.index', compact('cart')); // Pasa el carrito a la vista
    }

    /**
     * Añade un producto al carrito.
     */
    public function add(Request $request, Product $product)
    {
        // Validar la cantidad (debe ser número positivo, permitiendo decimales)
        $request->validate([
            'quantity' => 'required|numeric|min:0.1'
        ]);

        // Convertir a float para asegurar el manejo de decimales
        $quantityToAdd = floatval($request->quantity);

        // Verificar si hay stock suficiente ANTES de añadir al carrito
        if (floatval($product->stock) < $quantityToAdd) {
            return redirect()->back()->with('error', "Stock insuficiente para {$product->nombre}. Disponible: {$product->stock} Kg");
        }

        $cart = Session::get('cart', []);

        // Si el producto ya está en el carrito, sumar la cantidad
        if (isset($cart[$product->id])) {
            $newQuantity = (float)$cart[$product->id]['quantity'] + $quantityToAdd;

            // Volver a verificar stock con la nueva cantidad total
            if (floatval($product->stock) < $newQuantity) {
                 return redirect()->back()->with('error', "Stock insuficiente para añadir más {$product->nombre}. Disponible: {$product->stock} Kg. Ya tienes {$cart[$product->id]['quantity']} Kg en el carrito.");
            }
            $cart[$product->id]['quantity'] = $newQuantity;

        } else {
            // Si es un producto nuevo, añadirlo al carrito
            $cart[$product->id] = [
                "name" => $product->nombre, // Guardamos el nombre
                "quantity" => $quantityToAdd,
                "price" => $product->precio, // Guardamos el precio 
                
            ];
        }

        Session::put('cart', $cart); // Guardar el carrito actualizado en la sesión

        return redirect()->route('cart.index')->with('success', '¡Producto añadido al carrito!'); // Redirigir a la vista del carrito
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.1' // Permitir decimales, debe ser positivo
        ]);

        $cart = Session::get('cart');
        $newQuantity = floatval($request->quantity);

        if (isset($cart[$id])) {

            // Volvemos a buscar el producto para asegurar datos y verificar stock
            $product = Product::find($id);

            if ($product) {
                 // Verificar stock ANTES de actualizar
                 if(floatval($product->stock) < $newQuantity) {
                      return redirect()->route('cart.index')->with('error', "Stock insuficiente para {$product->nombre}. Disponible: {$product->stock} Kg");
                 }

                // Actualizar el item completo en el carrito para asegurar que tenemos name y price
                $cart[$id] = [
                    "name" => $product->nombre, // Validacion el nombre
                    "quantity" => $newQuantity,
                    "price" => $product->precio // Validacion el precio actual
                ];
                Session::put('cart', $cart); // Guardamos el carrito actualizado
                return redirect()->route('cart.index')->with('success', 'Cantidad actualizada.');

            } else {
                // Si el producto ya no existe en la BD, lo quitamos del carrito
                unset($cart[$id]);
                Session::put('cart', $cart);
                return redirect()->route('cart.index')->with('error', 'El producto ya no existe y fue eliminado del carrito.');
            }
             

        } else {
            return redirect()->route('cart.index')->with('error', 'Producto no encontrado en el carrito.');
        }
        // Las líneas de guardar sesión 
    }


    /**
     * Elimina un producto del carrito.
     */
    public function remove($id)
    {
        $cart = Session::get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]); // Elimina el item del array del carrito
            Session::put('cart', $cart); // Guarda el carrito actualizado en la sesión
        }

        // Siempre redirigir
        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
    }
}

