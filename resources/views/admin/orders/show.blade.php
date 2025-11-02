{{--
Esta es la página de DETALLE para que el EMPLEADO/ADMIN gestione un pedido.
Usa el layout 'x-app-layout' que ya tienes.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Pedido') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Volver a la lista de pedidos
            </a>
        </div>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Columna Izquierda: Detalles y Productos -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Productos en el Pedido -->
                    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Productos del Pedido</h3>
                        <div class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <div class="py-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <!-- Placeholder de imagen -->
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 mr-4 flex-shrink-0">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1.586-1.586a2 2 0 00-2.828 0L6 14m6-6l.01.01"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $item->product->nombre }}</p>
                                            <p class="text-sm text-gray-600">
                                                {{ $item->cantidad }} Kg x ${{ number_format($item->precio_unitario, 0) }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        ${{ number_format($item->cantidad * $item->precio_unitario, 0) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                        <!-- Total -->
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <div class="flex justify-between items-center text-2xl font-bold text-green-600">
                                <span>Total del Pedido</span>
                                <span>${{ number_format($order->total, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Cliente y Envío -->
                    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Información del Cliente y Envío</h3>
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Cliente</h4>
                                <p class="text-lg text-gray-900">{{ $order->user->name }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Email</h4>
                                <p class="text-lg text-gray-900">{{ $order->user->email }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Dirección de Envío</h4>
                                <p class="text-lg text-gray-900 whitespace-pre-wrap">{{ $order->direccion_envio }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Gestión de Estados (Tu requisito) -->
                <div class="lg:col-span-1 space-y-8">
                    
                    <!-- Formulario de Gestión -->
                    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Gestionar Pedido</h3>
                        
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('POST') <!-- Usamos POST simple -->

                            <!-- 1. Estado del Pedido (Despacho) -->
                            <div class="mb-6">
                                <label for="estado_pedido" class="block text-sm font-medium text-gray-700 mb-2">Estado del Despacho</label>
                                <select id="estado_pedido" name="estado_pedido" 
                                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="pendiente despacho" @selected($order->estado_pedido == 'pendiente despacho')>Pendiente Despacho</option>
                                    <option value="despachada" @selected($order->estado_pedido == 'despachada')>Despachada</option>
                                    <option value="enviada" @selected($order->estado_pedido == 'enviada')>Enviada</option>
                                    <option value="entregada" @selected($order->estado_pedido == 'entregada')>Entregada</option>
                                </select>
                            </div>

                            <!-- 2. Estado del Pago -->
                            <div class="mb-8">
                                <label for="estado_pago" class="block text-sm font-medium text-gray-700 mb-2">Estado del Pago</label>
                                <select id="estado_pago" name="estado_pago" 
                                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="pendiente" @selected($order->estado_pago == 'pendiente')>Pendiente</option>
                                    <option value="pagada" @selected($order->estado_pago == 'pagada')>Pagada</option>
                                </select>
                                @if($order->metodo_pago == 'efectivo')
                                    <p class="text-xs text-blue-600 mt-2">
                                        Este es un pago contra entrega. Pasa a 'Pagada' cuando el domiciliario confirme la recepción del dinero.
                                    </p>
                                @endif
                            </div>

                            <!-- 3. Botón de Actualizar -->
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 text-lg">
                                Actualizar Estados
                            </button>
                        </form>
                    </div>

                    <!-- Información de Pago -->
                    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Información de Pago</h3>
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Método de Pago</h4>
                                <p class="text-lg text-gray-900 font-semibold">{{ ucfirst($order->metodo_pago) }}</p>
                            </div>
                            
                            @if($order->metodo_pago == 'transferencia')
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Referencia de Pago</h4>
                                    <p class="text-lg text-gray-900">{{ $order->referencia_pago ?? 'N/A' }}</p>
                                </div>
                            @endif

                            @if($order->metodo_pago == 'efectivo')
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Paga con (Efectivo)</h4>
                                    <p class="text-lg text-gray-900">${{ number_format($order->monto_efectivo, 0) }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Cambio a devolver</h4>
                                    <p class="text-lg text-gray-900 font-bold text-red-600">
                                        ${{ number_format($order->monto_efectivo - $order->total, 0) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

