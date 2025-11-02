<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Banner con degradado naranja --}}
            <div class="bg-gradient-to-r from-orange-400 to-red-500 text-white rounded-lg shadow-lg mb-8">
                <div class="p-6 flex justify-between items-center">
                    {{-- Lado izquierdo: Título y contador --}}
                    <div>
                        <h1 class="text-3xl font-bold mb-1">🛒 Historial y Reporte de Compras</h1>
                        <p class="text-orange-100">{{ $purchases->count() }} registros en total</p>
                    </div>
                    {{-- Lado derecho: Botón "+ Registrar Nueva Compra" (solo para Admin) --}}
                    <div>
                        @if(auth()->user()->role->name == 'Administrador')
                            <a href="{{ route('purchases.create') }}" class="bg-white hover:bg-gray-50 text-orange-700 font-bold py-2 px-4 rounded shadow transition-colors duration-200">
                                + Registrar Nueva Compra
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-xl sm:rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filtrar Compras</h3>
                <form action="{{ route('purchases.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700">Desde</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700">Hasta</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="provider_id" class="block text-sm font-medium text-gray-700">Proveedor</label>
                            <select name="provider_id" id="provider_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}" {{ request('provider_id') == $provider->id ? 'selected' : '' }}>
                                        {{ $provider->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">Filtrar</button>
                            <a href="{{ route('purchases.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">Limpiar</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Compras (Filtradas)</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalPurchases }}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Artículos Comprados</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalItems }}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Costo Total (Completadas)</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">${{ number_format($totalCost, 2) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registró</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($purchases as $purchase)
                            <tr class="{{ $purchase->status == 'anulada' ? 'bg-red-50 opacity-60' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->provider->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $purchase->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($purchase->total_cost, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $purchase->status == 'anulada' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <a href="{{ route('purchases.show', $purchase) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                    
                                    {{-- ENLACE PDF AÑADIDO --}}
                                    <a href="{{ route('purchases.pdf', $purchase) }}" target="_blank" class="text-gray-600 hover:text-gray-900">PDF</a>

                                    {{-- HU4 y HU5: Solo el Admin puede anular y solo si no está ya anulada --}}
                                    @if(auth()->user()->role->name == 'Administrador' && $purchase->status != 'anulada')
                                    <form action="{{ route('purchases.void', $purchase) }}" method="POST" class="inline-block form-anular">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900">Anular</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    No hay compras registradas que coincidan con los filtros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    @push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Capturamos todos los formularios que tengan la clase "form-anular"
        const formsAnular = document.querySelectorAll('.form-anular');
        formsAnular.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Detenemos el envío normal

                Swal.fire({
                    title: '¿Estás seguro de ANULAR?',
                    text: "¡Esta acción revertirá el inventario y no se puede deshacer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Rojo
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, anular compra',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    // Si el usuario confirma, enviamos el formulario
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
    @endpush

</x-app-layout>