<?php

namespace App\Http\Controllers;

use App\Models\Product; // Importar Product
use App\Models\Sale;    // Importar Sale
use App\Models\Purchase;
use App\Models\Order;   // Importar el modelo Order
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total de productos registrados 
        $totalProducts = Product::count(); 

        // Total de ingresos por ventas 
        $totalSales = Sale::where('estado', 'pagada')->sum('total'); 

        // Productos con bajo stock 
        $lowStockProducts = Product::where('stock', '<', 10)->where('is_active', true)->get();

        // --- Total de Compras ---
        $totalPurchases = Purchase::where('status', 'completada')->sum('total_cost');

        
        // --- MÉTRICAS DE PEDIDOS ---
        
        // Total de pedidos pendientes de despacho (para el Admin/Empleado)
        $totalPedidosPendientes = Order::where('estado_pedido', 'pendiente despacho')->count();
        
        // Total de ingresos por pedidos de clientes (que ya están pagados)
        $ingresosPorPedidos = Order::where('estado_pago', 'pagada')->sum('total');

        // --- ---


        return view('dashboard', compact(
            'totalProducts', 
            'totalSales', 
            'lowStockProducts', 
            'totalPurchases',
            'totalPedidosPendientes', 
            'ingresosPorPedidos'      
        ));
    }
}

