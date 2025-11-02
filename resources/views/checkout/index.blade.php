{{--
Esta es la página de Checkout (Paso 5).
Usa la plantilla 'store' que ya creamos.
--}}
@extends('layouts.store')

@section('title', 'Finalizar Compra')

@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-12">

    <h1 class="text-3xl font-bold text-gray-800 mb-8">Finalizar Compra</h1>

    <!-- Mensajes de feedback (Errores de stock, etc.) -->
    @if (session('error'))
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow" role="alert">
            <h3 class="font-bold">Error</h3>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- 
    Usamos x-data de Alpine.js (que ya viene con Laravel)
    para mostrar/ocultar los campos de pago dinámicamente.
    --}}
    <form action="{{ route('checkout.store') }}" method="POST" x-data="{ metodo_pago: 'efectivo' }">
        @csrf

        <div class="bg-white rounded-xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-0">
            
            <!-- Columna Izquierda: Formulario -->
            <div class="p-6 md:p-8">
                <!-- 1. Dirección de Envío -->
                <div class="mb-6">
                    <label for="direccion_envio" class="block text-sm font-medium text-gray-700 mb-2">Dirección de Envío</label>
                    <textarea id="direccion_envio" name="direccion_envio" rows="4" 
                              class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                              placeholder="Escribe tu dirección completa, barrio, y cualquier detalle adicional." 
                              required>{{ old('direccion_envio') }}</textarea>
                    @error('direccion_envio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 2. Método de Pago (Tu requisito) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                    <div class="space-y-3">
                        <!-- Opción 1: Efectivo (Contra entrega) -->
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="metodo_pago" value="efectivo" x-model="metodo_pago" class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Efectivo (Pago contra entrega)</span>
                                <span class="block text-xs text-gray-500">Pagas al domiciliario cuando recibas tu pedido.</span>
                            </div>
                        </label>
                        <!-- Opción 2: Transferencia -->
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="metodo_pago" value="transferencia" x-model="metodo_pago" class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Transferencia (Nequi/Bancolombia)</span>
                                <span class="block text-xs text-gray-500">Te daremos los datos al confirmar.</span>
                            </div>
                        </label>
                    </div>
                    @error('metodo_pago')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 3. Campos Dinámicos de Pago (Tu requisito) -->
                
                <!-- Si es Efectivo (Tu requisito de "devuelta") -->
                <div x-show="metodo_pago === 'efectivo'" class="mb-6">
                    <label for="monto_efectivo" class="block text-sm font-medium text-gray-700 mb-2">¿Con cuánto vas a pagar?</label>
                    <input type="number" id="monto_efectivo" name="monto_efectivo" 
                           class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                           placeholder="Ej: 50000" 
                           {{-- 
                           Aquí no podemos poner min="{{ $total }}" porque $total no está disponible en Alpine
                           lo validaremos en el controlador.
                           --}}
                           min="0"
                           value="{{ old('monto_efectivo') }}">
                    <p class="text-xs text-gray-500 mt-1">Para saber si debemos llevar cambio ("devuelta").</p>
                    @error('monto_efectivo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Si es Transferencia (Tu requisito de "referencia") -->
                <div x-show="metodo_pago === 'transferencia'" class="mb-6">
                    <label for="referencia_pago" class="block text-sm font-medium text-gray-700 mb-2">Número de Referencia</label>
                    <input type="text" id="referencia_pago" name="referencia_pago" 
                           class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                           placeholder="Ej: 12345678" 
                           value="{{ old('referencia_pago') }}">
                    <p class="text-xs text-gray-500 mt-1">El número de comprobante de tu transferencia.</p>
                     @error('referencia_pago')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Columna Derecha: Resumen del Pedido -->
            <div class="bg-gray-50 p-6 md:p-8 border-l border-gray-100">
                <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">Resumen de tu Pedido</h2>
                
                <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                    {{-- Esta vista se carga desde el CheckoutController, que no pasa $cart
                         sino que lo debe leer de la sesión.
                         Vamos a leerlo de la sesión directamente aquí. --}}
                    @php
                        $cart = session('cart', []);
                        $total = 0;
                        foreach ($cart as $id => $details) {
                            $total += $details['price'] * $details['quantity'];
                        }
                    @endphp

                    @forelse($cart as $id => $details)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <!-- Placeholder de imagen -->
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 mr-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1.586-1.586a2 2 0 00-2.828 0L6 14m6-6l.01.01"></path></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $details['name'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $details['quantity'] }} Kg x ${{ number_format($details['price'], 0) }}</p>
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">${{ number_format($details['price'] * $details['quantity'], 0) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Tu carrito está vacío.</p>
                    @endforelse
                </div>

                <!-- Total -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-medium text-gray-600">Subtotal</span>
                        <span class="text-lg font-bold text-gray-900">${{ number_format($total, 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-medium text-gray-600">Envío</span>
                        <span class="text-lg font-bold text-gray-900">Gratis</span>
                    </div>
                    <div class="flex justify-between items-center text-2xl font-bold text-green-600">
                        <span>Total a Pagar</span>
                        <span>${{ number_format($total, 0) }}</span>
                    </div>
                </div>

                <!-- Botón de Confirmar -->
                <div class="mt-8">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center text-lg"
                        @if(empty($cart)) disabled @endif>
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Confirmar Pedido
                    </button>
                    @if(empty($cart))
                        <p class="text-red-500 text-xs text-center mt-2">No puedes confirmar un pedido con el carrito vacío.</p>
                    @endif
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

