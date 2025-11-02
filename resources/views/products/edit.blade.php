<x-app-layout>
    

    <div class="py-12">
        {{--ancho del formulario --}}
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> 

            {{-- ===== BANNER (Usaremos un degrado verde ===== --}}
            <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg shadow-lg mb-6">
                <div class="p-6">
                    <div>
                        <h2 class="text-2xl font-bold">Editar Producto</h2>
                        <p class="text-green-100 text-sm mt-1">Modifica la información del producto: {{ $product->nombre }}</p>
                    </div>
                </div>
            </div>
            {{-- ===== FIN: BANNER ===== --}}

            {{-- Tarjeta Blanca para el Formulario --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-8">
                    <h3 class="text-xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Actualizar información del producto
                    </h3>

                    <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nombre --}}
                        <div>
                            <label for="nombre" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                Nombre del Producto *
                            </label>
                            <input type="text" id="nombre" name="nombre"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('nombre', $product->nombre) }}" required>
                             @error('nombre') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label for="descripcion" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                Descripción
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('descripcion', $product->descripcion) }}</textarea>
                             @error('descripcion') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Precio y Stock --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="precio" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                                    Precio (/Kg) * {{-- Añadido /Kg --}}
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                                    <input type="number" step="0.01" min="0" id="precio" name="precio"
                                           class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                           value="{{ old('precio', $product->precio) }}" required>
                                </div>
                                @error('precio') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="stock" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Stock Actual (Kg) * {{-- Añadido Kg --}}
                                </label>
                                {{-- Input Stock con step="0.01" --}}
                                <input type="number" step="0.01" min="0" id="stock" name="stock"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('stock', $product->stock) }}" required>
                                @error('stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-100 mt-6">
                            <a href="{{ route('products.index') }}">
                                <x-secondary-button type="button">
                                    Cancelar
                                </x-secondary-button>
                            </a>
                            <x-primary-button>
                                Actualizar Producto
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

