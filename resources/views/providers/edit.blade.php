<x-app-layout>
    

    <div class="py-12">
        {{-- Mismo max-width que en create --}}
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8"> 

            {{-- ===== BANNER ===== --}}
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg shadow-lg mb-6">
                <div class="p-6">
                    <div>
                        
                        <h2 class="text-2xl font-bold">Editar Proveedor</h2>
                        <p class="text-blue-100 text-sm mt-1">Modifica los datos del proveedor seleccionado.</p>
                    </div>
                </div>
            </div>
            {{-- ===== FIN: BANNER ===== --}}

            {{-- Tarjeta Blanca para el Formulario --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 border border-gray-100"> 
                
                <form action="{{ route('providers.update', $provider) }}" method="POST" class="space-y-6"> 
                    @csrf
                    @method('PUT') 
                    
                    
                    <div>
                        <label for="name" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Nombre del Proveedor *
                        </label> 
                        
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', $provider->name) }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            placeholder="Ej: Frutas del Campo S.A." 
                            required>
                        @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror 
                    </div>

                    {{-- Phone Field con Icono --}}
                    <div>
                        <label for="phone" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                             </svg>
                            Teléfono (Opcional)
                        </label>
                        
                        <input 
                            type="tel" 
                            name="phone" 
                            id="phone" 
                            value="{{ old('phone', $provider->phone) }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            placeholder="Ej: 3001234567"> 
                        @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div class="flex justify-end pt-4 space-x-3 border-t border-gray-200 mt-8"> 
                        <a href="{{ route('providers.index') }}">
                            <x-secondary-button type="button"> 
                                Cancelar
                            </x-secondary-button>
                        </a>
                        {{-- Cambiamos el texto del botón primario --}}
                        <x-primary-button>
                            Actualizar Proveedor
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Caja de Consejos --}}
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800">Consejos para editar proveedores</h4>
                        <ul class="list-disc list-inside mt-2 text-sm text-blue-700 space-y-1">
                            <li>Modifica solo los campos necesarios.</li>
                            <li>Recuerda guardar los cambios al finalizar.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>