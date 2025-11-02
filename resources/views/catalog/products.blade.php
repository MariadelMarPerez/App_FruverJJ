@extends('layouts.store')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">

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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Nuestro Catálogo</h1>

        {{-- Verificamos si la colección de productos está vacía --}}
        @if($products->isEmpty())
            <div class="py-12 px-4 text-center text-gray-500">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M6 13H4M20 13l-4 4-4-4M6 13l4 4 4-4"></path>
                </svg>
                <p class="text-xl font-semibold">No hay productos disponibles.</p>
                <p class="text-gray-400 mt-2">Por favor, vuelve a intentarlo más tarde.</p>
            </div>
        @else
            <!-- Listado de Productos -->
            {{--  el grid y el bucle --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden flex flex-col transition-transform duration-300 hover:shadow-xl hover:-translate-y-1">

                        <div class="p-5 flex flex-col flex-grow">
                            <h5 class="text-xl font-bold tracking-tight text-gray-900 mb-2">{{ $product->nombre }}</h5>
                            {{-- Verificamos sintaxis de number_format --}}
                            <p class="text-lg font-semibold text-green-600 mb-3">${{ number_format($product->precio, 0) }} / Kg</p>
                            <p class="mb-4 text-sm text-gray-600 flex-grow">
                                {{ $product->descripcion ?? 'Producto fresco de la mejor calidad.' }}
                            </p>

                            <!-- Formulario para añadir al carrito -->
                            {{-- Reconstruimos el formulario --}}
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <div class="flex items-center gap-3 mb-3">
                                    <label for="quantity-{{ $product->id }}" class="text-sm font-medium text-gray-700">Cant (Kg):</label>
                                    <input type="number" id="quantity-{{ $product->id }}" name="quantity"
                                           class="w-24 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                                           value="1" min="0.1" step="0.1" required>
                                </div>
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l.21-2H12.6a1 1 0 00.96-.72l3-6A1 1 0 0015 3H4.852L4.491 1.29A1 1 0 003 1zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                                    </svg>
                                    Añadir al Carrito
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8">
                {{-- Verificamos sintaxis de links() --}}
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

