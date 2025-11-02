<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Banner Naranja --}}
            <div class="bg-gradient-to-r from-orange-400 to-red-500 text-white rounded-lg shadow-lg mb-6">
                <div class="p-6">
                    <div>
                        <h2 class="text-2xl font-bold">Registrar Nueva Compra</h2>
                        <p class="text-orange-100 text-sm mt-1">Ingresa los datos de la compra y actualiza el inventario.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('purchases.store') }}" method="POST">
                @csrf
                {{-- Tarjeta Blanca para Datos del Proveedor --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 border border-gray-100 mb-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-6 flex items-center">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                         </svg>
                        Datos del Proveedor
                    </h3>
                    <div>
                        <label for="provider_id" class="block text-sm font-medium text-gray-700 mb-1">Proveedor *</label>
                        <select name="provider_id" id="provider_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Seleccione un proveedor</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        @error('provider_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Tarjeta Blanca para Productos --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-700 mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                         </svg>
                        Productos de la Compra
                    </h3>

                    <div id="product-list" class="space-y-4 border-b border-gray-200 pb-4 mb-4">
                        <!-- Primera Fila de Producto -->
                        <div class="grid grid-cols-12 gap-4 items-end product-row">
                            <div class="col-span-5">
                                <label class="block text-sm font-medium text-gray-700">Producto *</label>
                                <select name="products[0][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 product-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($products as $product)
                                        {{-- Asegúrate que aquí usas el nombre correcto de tu columna (ej: nombre) --}}
                                        <option value="{{ $product->id }}">{{ $product->nombre }} (Stock: {{ number_format(floatval($product->stock ?? 0), 2) }} Kg)</option> {{-- Añadido Kg y formato decimal --}}
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Cantidad (Kg) *</label> {{-- Añadido Kg --}}
                                {{-- Añadido step="0.01" min="0.01" --}}
                                <input type="number" name="products[0][quantity]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input" step="0.01" min="0.01" required placeholder="Ej: 1.5">
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Costo Unitario (/Kg) *</label> {{-- Añadido /Kg --}}
                                <input type="number" name="products[0][cost]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 cost-input" step="0.01" min="0" required placeholder="Ej: 1500.00">
                            </div>
                            <div class="col-span-1">
                                <button type="button" class="bg-red-200 text-red-600 p-2 rounded opacity-50 cursor-not-allowed text-xs" disabled title="No se puede quitar la primera fila">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                       <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                     </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    @error('products') <span class="text-red-500 text-sm mt-1 -mb-2 block">{{ $message }}</span> @enderror

                    <button type="button" id="add-product-btn" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Añadir Otro Producto
                    </button>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end pt-6 space-x-3 mt-6 border-t border-gray-200">
                        <a href="{{ route('purchases.index') }}">
                            <x-secondary-button type="button">
                                Cancelar
                            </x-secondary-button>
                        </a>
                        <x-primary-button>
                            Registrar Compra
                        </x-primary-button>
                    </div>
                </div> {{-- Fin Tarjeta Productos --}}
            </form>

            {{-- Caja de Consejos (Naranja) --}}
            <div class="mt-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                 <div class="flex items-start">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                     </svg>
                     <div>
                         <h4 class="text-sm font-semibold text-orange-800">Consejos para registrar compras</h4>
                         <ul class="list-disc list-inside mt-2 text-sm text-orange-700 space-y-1">
                             <li>Selecciona primero el proveedor.</li>
                             <li>Ingresa la cantidad en Kilogramos (puedes usar decimales, ej: 1.5).</li>
                             <li>El costo unitario también debe ser por Kilogramo.</li>
                             <li>El stock se actualizará automáticamente al registrar la compra.</li>
                             <li>Puedes añadir varios productos a la misma compra con el botón "+ Añadir".</li>
                         </ul>
                     </div>
                 </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let productIndex = 1;
            const productList = document.getElementById('product-list');
            const addProductBtn = document.getElementById('add-product-btn');

            // Opciones de productos (Añadido Kg y formato decimal al stock)
            const productOptions = `@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->nombre }} (Stock: {{ number_format(floatval($product->stock ?? 0), 2) }} Kg)</option>@endforeach`;

            function addNewRow() {
                const newRow = document.createElement('div');
                newRow.className = 'grid grid-cols-12 gap-4 items-end product-row';

                newRow.innerHTML = `
                    <div class="col-span-5">
                        <select name="products[${productIndex}][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 product-select" required>
                            <option value="">Seleccione...</option>
                            ${productOptions}
                        </select>
                    </div>
                    <div class="col-span-3">
                         {{-- Input cantidad con step y min para decimales --}}
                        <input type="number" name="products[${productIndex}][quantity]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input" step="0.01" min="0.01" required placeholder="Kg">
                    </div>
                    <div class="col-span-3">
                        <input type="number" name="products[${productIndex}][cost]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 cost-input" step="0.01" min="0" required placeholder="Costo /Kg">
                    </div>
                    <div class="col-span-1">
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white p-2 rounded remove-product-btn text-xs" title="Quitar producto">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                             </svg>
                        </button>
                    </div>
                `;

                productList.appendChild(newRow);
                productIndex++;
            }

            addProductBtn.addEventListener('click', addNewRow);

            productList.addEventListener('click', function (e) {
                const removeButton = e.target.closest('.remove-product-btn');
                if (removeButton) {
                    removeButton.closest('.product-row').remove();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

