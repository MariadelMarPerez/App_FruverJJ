@extends('layouts.store')

@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-12">

    <!-- Mensajes -->
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Mi Carrito de Compras</h1>

        @if(empty($cart))
            <div class="py-12 px-4 text-center text-gray-500">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-xl font-semibold">Tu carrito está vacío.</p>
                <a href="{{ route('catalog.index') }}" class="mt-4 inline-block text-green-600 hover:text-green-800 font-medium">
                    &larr; Volver al catálogo
                </a>
            </div>
        @else
            <div class="overflow-x-auto mb-8">
                <table class="w-full min-w-[600px] text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3 px-6">Producto</th>
                            <th scope="col" class="py-3 px-6">Precio Unit. (Kg)</th>
                            <th scope="col" class="py-3 px-6">Cantidad (Kg)</th>
                            <th scope="col" class="py-3 px-6">Subtotal</th>
                            <th scope="col" class="py-3 px-6">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $finalTotal = 0; @endphp
                        @foreach($cart as $id => $details)
                            @php
                                // Calcular subtotal asegurándose de que los valores son numéricos
                                $price = $details['price'] ?? 0;
                                $quantity = $details['quantity'] ?? 0;
                                $subtotal = 0;
                                if(is_numeric($price) && is_numeric($quantity)){
                                   $subtotal = $price * $quantity;
                                   $finalTotal += $subtotal;
                                }
                            @endphp
                            <tr class="bg-white border-b hover:bg-gray-50 align-middle">
                                <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $details['name'] ?? 'Producto no encontrado' }}
                                </th>
                                <td class="py-4 px-6">
                                    ${{ number_format($price, 0) }}
                                </td>
                                <td class="py-4 px-6">
                                    <!-- Formulario para actualizar cantidad -->
                                    {{--  --}}
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $quantity }}"
                                               min="0.1" step="0.1" required
                                               class="w-20 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-sm">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                            Act.
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-900">
                                    ${{ number_format($subtotal, 0) }}
                                </td>
                                <td class="py-4 px-6">
                                    <!-- Formulario para eliminar -->
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-800 text-xs">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total y Botón de Checkout -->
            <div class="flex flex-col items-end space-y-4">
                <div class="text-right">
                    {{-- El total final --}}
                    <p class="text-xl font-bold text-gray-900">Total: ${{ number_format($finalTotal, 0) }}</p>
                    <p class="text-sm text-gray-500">Impuestos incluidos</p>
                </div>
                <div class="flex space-x-4 w-full sm:w-auto">
                     <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 transition text-center text-sm font-medium">
                        &larr; Seguir comprando
                    </a>
                    <a href="{{ route('checkout.index') }}" class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition text-center text-sm font-medium">
                        Proceder al Pago &rarr;
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
