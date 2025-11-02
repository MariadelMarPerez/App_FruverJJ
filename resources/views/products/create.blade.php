<x-app-layout>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- ===== BANNER Verde ===== --}}
            <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg shadow-lg mb-6">
                <div class="p-6">
                    <div>
                        <h2 class="text-2xl font-bold">Nuevo Producto</h2>
                        <p class="text-green-100 text-sm mt-1">Agrega un nuevo producto al inventario</p>
                    </div>
                </div>
            </div>
            {{-- ===== FIN: BANNER ===== --}}

            {{-- Tarjeta Blanca para el Formulario --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-8">
                     <h3 class="text-xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Información del Producto
                    </h3>

                    <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Nombre --}}
                        <div>
                            <label for="nombre" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                Nombre del Producto *
                            </label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required placeholder="Ej: Manzana Roja">
                            @error('nombre') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- ===== INICIO: LISTA DE NOMBRES EXISTENTES ===== --}}
                        {{-- Solo se muestra si la variable existe y tiene elementos --}}
                        @isset($existingProductNames)
                            @if($existingProductNames->isNotEmpty())
                                <div class="mt-2 text-xs text-gray-500 bg-gray-50 p-3 rounded-md border border-gray-200">
                                    <span class="font-medium block mb-1">Productos activos ya registrados:</span>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($existingProductNames as $name)
                                            <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full text-[10px]">{{ $name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endisset
                        {{-- ===== FIN: LISTA DE NOMBRES EXISTENTES ===== --}}


                        {{-- Descripción --}}
                        <div>
                            <label for="descripcion" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                Descripción (Opcional)
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Describe las características del producto...">{{ old('descripcion') }}</textarea>
                            @error('descripcion') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Precio y Stock --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="precio" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                                    Precio de Venta (/Kg) * </label>
                                <div class="relative mt-1"> {{-- Añadido --}}
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">$</span> 
                                    <input type="number" step="0.01" min="0" id="precio" name="precio" value="{{ old('precio') }}"
                                           class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                           required placeholder="0.00">
                                </div>
                                @error('precio') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="stock" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Stock Inicial (Kg) * </label>
                                <input type="number" step="0.01" min="0" id="stock" name="stock" value="{{ old('stock', 0) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required placeholder="0.00">
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
                                Crear Producto
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tips Card (Verde) --}}
            <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                     <svg class="w-6 h-6 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     <div>
                        <h4 class="text-green-800 font-semibold mb-2">💡 Consejos para crear productos</h4>
                        <ul class="text-green-700 text-sm space-y-1">
                            <li>Usa nombres claros (ej: "Manzana Roja Grande", "Aguacate Hass").</li>
                            <li>El precio de venta debe ser por Kilogramo.</li>
                            <li>El stock inicial también es en Kilogramos (puedes usar decimales).</li>
                            <li>Si aún no tienes stock, puedes poner 0.</li>
                            <li>Revisa la lista de productos existentes para evitar duplicados.</li> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

