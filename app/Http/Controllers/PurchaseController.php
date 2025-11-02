<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Provider;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log; 

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['provider', 'user', 'details']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        $purchases = $query->orderBy('created_at', 'desc')->get();
        $providers = Provider::all();

        $filteredPurchases = $purchases->where('status', 'completada');
        $totalCost = $filteredPurchases->sum('total_cost');
        $totalItems = $filteredPurchases->sum(function($purchase) {
            // Sumar cantidades como float
            return $purchase->details->sum(function($detail){
                 return floatval($detail->quantity);
             });
        });
        $totalPurchases = $filteredPurchases->count();

        return view('purchases.index', compact(
            'purchases',
            'providers',
            'totalCost',
            'totalItems',
            'totalPurchases'
        ));
    }

    public function create()
    {
        $providers = Provider::all();
        //  Product tiene 'stock' como decimal
        $products = Product::orderBy('nombre')->get();
        return view('purchases.create', compact('providers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0.01', // Acepta decimales > 0
            'products.*.cost' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalCost = 0;
            foreach ($request->products as $item) {
                // Usar floatval para cálculos precisos
                $totalCost += floatval($item['quantity']) * floatval($item['cost']);
            }

            $purchase = Purchase::create([
                'provider_id' => $request->provider_id,
                'user_id' => Auth::id(),
                'total_cost' => $totalCost, // Guardar total calculado
                'status' => 'completada',
            ]);

            foreach ($request->products as $item) {
                $purchase->details()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => floatval($item['quantity']), // Guardar como float
                    'cost' => floatval($item['cost']),
                ]);

                $product = Product::find($item['product_id']);
                // Sumar al stock como float
                $product->stock = floatval($product->stock) + floatval($item['quantity']);
                $product->save();
            }

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Compra registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error Purchase Store: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al registrar la compra. Revisa los datos. Detalles: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('provider', 'user', 'details.product');
        return view('purchases.show', compact('purchase'));
    }

    public function void(Purchase $purchase)
    {
        if ($purchase->status == 'anulada') {
            return redirect()->back()->with('error', 'Esta compra ya ha sido anulada.');
        }

        DB::beginTransaction();
        try {
            foreach ($purchase->details as $detail) {
                $product = $detail->product;
                if ($product) {
                    $quantityToRevert = floatval($detail->quantity);
                    $currentStock = floatval($product->stock);

                    // Revertir stock como float
                
                    $product->stock = $currentStock - $quantityToRevert;
                    // Asegurar  el stock no sea negativo 
                    if ($product->stock < 0) {
                         Log::warning("Stock negativo después de anular compra ID {$purchase->id} para producto ID {$product->id}. Stock resultante: {$product->stock}");
                         $product->stock = 0; 
                    }
                    $product->save();
                } else {
                     Log::warning("Producto ID {$detail->product_id} no encontrado al intentar anular compra ID {$purchase->id}.");
                }
            }

            $purchase->status = 'anulada';
            $purchase->save();

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Compra anulada exitosamente. El inventario ha sido revertido.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error Purchase Void ID {$purchase->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al anular la compra: ' . $e->getMessage());
        }
    }

    public function generatePDF(Purchase $purchase)
    {
        $purchase->load('provider', 'user', 'details.product');
        $pdf = Pdf::loadView('purchases.pdf', compact('purchase'));
        return $pdf->download('compra-' . $purchase->id . '.pdf');
    }

    
    public function edit(Purchase $purchase) { abort(404); }
    public function update(Request $request, Purchase $purchase) { abort(404); }
    
}

