<?php

namespace App\Http\Controllers\Admin; // <--  'Admin'

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    /*
     * lista de todos los pedidos recibidos.
     * Pagina para Administradores y Empleados.
     */
    public function index()
    {
        // Se obtiene todos los pedidos por el cliente
        // Orden Ascendente
        $orders = Order::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20); // Paginacion

        // Retornamos la vista 
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Muestra el detalle de un pedido específico.
     * El admin/empleado verá los detalles para despachar.
     */
    public function show(Order $order)
    {
        // Relaciones
        // 'user' -> el cliente que hizo el pedido
        // 'items.product' -> los items del pedido y el producto asociado a cada item
        $order->load(['user', 'items.product']);

        // Retornamos la vista 
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Actualiza el estado de un pedido (despacho y/o pago).
     * la función que llama desde los botones en la vista 'show'.
     */
    public function updateStatus(Request $request, Order $order)
    {
        // 1. VALIDAMOS LOS DATOS (Tu requisito de estados)
        // Nos aseguramos de que los valores que lleguen sean solo los permitidos
        // en tu migración.
        $request->validate([
            'estado_pedido' => [
                'required',
                Rule::in(['pendiente despacho', 'despachada', 'enviada', 'entregada']),
            ],
            'estado_pago' => [
                'required',
                Rule::in(['pendiente', 'pagada']),
            ],
        ]);

        //  ACTUALIZACION PEDIDO
        try {
            $order->update([
                'estado_pedido' => $request->estado_pedido,
                'estado_pago' => $request->estado_pago,
            ]);

            // Si el pago es en efectivo y se marca como 'pagada',
        

            return redirect()->back()->with('success', '¡Estado del pedido actualizado exitosamente!');

        } catch (\Exception $e) {
            // Manejo error 
            return redirect()->back()->with('error', 'Hubo un error al actualizar el pedido: ' . $e->getMessage());
        }
    }
}

