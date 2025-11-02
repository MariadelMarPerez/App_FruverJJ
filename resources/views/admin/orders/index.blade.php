{{--
Esta es la página para que el EMPLEADO/ADMIN gestione los pedidos.
Usa el layout 'x-app-layout' que ya tienes.
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestionar Pedidos de Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensajes de feedback -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif


            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Pedidos Recibidos</h3>

        

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full min-w-max">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Pedido</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cliente</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado Pedido</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado Pago</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-700">{{ $order->user->name }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y h:i A') }}</td>
                                    <td class="py-4 px-4 text-sm font-semibold text-gray-800">${{ number_format($order->total, 0) }}</td>
                                    
                                    <!-- Etiqueta Estado Pedido (Tu requisito) -->
                                    <td class="py-4 px-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            @if($order->estado_pedido == 'pendiente despacho') bg-yellow-100 text-yellow-800
                                            @elseif($order->estado_pedido == 'despachada') bg-blue-100 text-blue-800
                                            @elseif($order->estado_pedido == 'enviada') bg-cyan-100 text-cyan-800
                                            @elseif($order->estado_pedido == 'entregada') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->estado_pedido) }}
                                        </span>
                                    </td>
                                    
                                    <!-- Etiqueta Estado Pago -->
                                    <td class="py-4 px-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            @if($order->estado_pago == 'pagada') bg-green-100 text-green-800
                                            @elseif($order->estado_pago == 'pendiente') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order->estado_pago) }}
                                        </span>
                                    </td>
                                    
                                    <td class="py-4 px-4 text-sm">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs transition-colors duration-200">
                                            Ver / Gestionar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 px-4 text-center text-gray-500">
                                        No se encontraron pedidos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

