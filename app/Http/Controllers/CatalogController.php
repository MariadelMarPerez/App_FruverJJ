<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Muestra el catálogo de productos.
     * Esta ruta debe estar protegida por el middleware 'auth'
     * y el 'role:Cliente' en el archivo de rutas.
     */
    public function index()
    {
        // Obtenemos solo productos activos y con stock
        // Usamos la lógica de tu 'ProductController'
        $products = Product::where('is_active', true)
                           ->where('stock', '>', 0)
                           ->orderBy('nombre', 'asc')
                           ->paginate(12); // Paginamos para no cargar todos de golpe

        // Retornamos la vista que crearemos en el siguiente paso
        return view('catalog.products', compact('products'));
    }
}

