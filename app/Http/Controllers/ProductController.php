<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Registro de Errores registrar
use Illuminate\Support\Facades\DB; // Busquedas
class ProductController extends Controller
{
    /**
     
     * Muestra todos los productos (activos e inactivos).
     */
    public function index(Request $request) 
    {
        $query = Product::query();

        // Filtro de búsqueda por nombre 
        if ($request->filled('search')) {
             $searchTerm = strtolower($request->search);
             $query->whereRaw('LOWER(nombre) LIKE ?', ['%' . $searchTerm . '%']);
        }

        // Filtro por estado de stock
        if ($request->filled('stock_filter')) {
            switch ($request->stock_filter) {
                case 'available':
                    $query->where('stock', '>', 0);
                    break;
                case 'low':
                    $query->where('stock', '<=', 10)->where('stock', '>', 0);
                    break;
                case 'out':
                    $query->where('stock', '=', 0);
                    break;
            }
        }

        //  El filtro por defecto.
        $statusFilter = $request->input('status_filter', 'all'); 
        
        // Aplicacion  filtro
        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }
        

        // Ordenamiento
        $query->orderBy('created_at', 'desc'); 

        $products = $query->paginate(12)->withQueryString();
        return view('products.index', compact('products'));
    }


    public function create()
    {
        // Pasamos los nombres para la lista de referencia
        $existingProductNames = Product::where('is_active', true)->orderBy('nombre')->pluck('nombre');
        return view('products.create', compact('existingProductNames'));
    }

    /**
     * 
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:products,nombre',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        try {
             Product::create([
                 'nombre' => $request->nombre,
                 'descripcion' => $request->descripcion,
                 'precio' => $request->precio,
                 'stock' => floatval($request->stock),
                 'is_active' => true 
             ]);
             return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
        } catch (\Exception $e) {
             Log::error("Error al crear producto: " . $e->getMessage());
             return back()->withInput()->with('error', 'Hubo un error al crear el producto. Intenta de nuevo.');
        }
    }

    /**
     * 
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * 
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * 
     */
    public function update(Request $request, string $id)
    {
         $product = Product::findOrFail($id); 

         $request->validate([
            'nombre' => 'required|string|max:255|unique:products,nombre,' . $product->id, 
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
         ]);

         try {
             $product->update([
                 'nombre' => $request->nombre,
                 'descripcion' => $request->descripcion,
                 'precio' => $request->precio,
                 'stock' => floatval($request->stock),
             ]);
             return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
         } catch (\Exception $e) {
             Log::error("Error al actualizar producto ID {$id}: " . $e->getMessage());
             return back()->withInput()->with('error', 'Hubo un error al actualizar el producto. Intenta de nuevo.');
         }
    }

    /**
     * Deshabilita un producto
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        try {
            $product->update(['is_active' => false]);
            return redirect()->route('products.index')->with('success', 'Producto deshabilitado exitosamente.');
        } catch (\Exception $e) {
             Log::error("Error al deshabilitar producto ID {$id}: " . $e->getMessage());
             return redirect()->route('products.index')->with('error', 'Hubo un error al deshabilitar el producto.');
        }
    }

    /**
     * Habilita un producto que estaba deshabilitado
     */
    public function enable(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update(['is_active' => true]);
            return redirect()->route('products.index')->with('success', 'Producto habilitado exitosamente.');
        } catch (\Exception $e) {
             Log::error("Error al habilitar producto ID {$id}: " . $e->getMessage());
             return redirect()->route('products.index')->with('error', 'Hubo un error al habilitar el producto.');
        }
    }
}
