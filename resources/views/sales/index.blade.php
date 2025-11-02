<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Banner Verde --}}
            <div class="bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-lg shadow-lg mb-8">
                <div class="p-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-1">🛒 Historial de Ventas</h1>
                        <p class="text-emerald-100">{{ $sales->total() }} ventas registradas en total (mostrando {{ $sales->count() }} en esta página)</p> 
                    </div>
                    <div class="flex space-x-3">
                        
                        {{--Botón PDF (Visible para todos los 3 roles) --}}
                        @if(in_array(auth()->user()->role->name, ['SuperAdministrador', 'Administrador', 'Empleado']))
                        <a href="{{ route('sales.pdf', request()->query()) }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Generar PDF
                        </a>
                        @endif

                        {{-- Botón Nueva Venta (Solo Admin y Empleado) --}}
                        @if(in_array(auth()->user()->role->name, ['Administrador','Empleado']))
                        <a href="{{ route('sales.create') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold hover:bg-green-50 text-sm transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Nueva Venta
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Filtros  --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filtros
                </h3>
                <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="product_search" class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                        <input type="text" name="product_search" id="product_search" value="{{ request('product_search') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" placeholder="Buscar producto...">
                    </div>
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    </div>
                     <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm">
                           <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Filtrar
                        </button>
                        <a href="{{ route('sales.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm" title="Limpiar filtros">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    </div>
                </form>
            </div>
            
            {{-- Resumen visble  para todos--}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                 <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Ventas (Esta Página)</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalVentas }}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Productos Vendidos (Pág.)</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $productosVendidos }} Kg</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Ingresos (Pág.)</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">${{ number_format($totalIngresos, 0) }}</p>
                </div>
            </div>

            {{-- Sales Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sales as $sale)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 {{ $sale->estado == 'cancelada' ? 'opacity-60 bg-red-50' : '' }}">
                        <div class="p-6">
                            {{-- Header Card --}}
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1 mr-3">
                                    <h3 class="text-lg font-bold text-gray-800 mb-1 truncate" title="{{ $sale->details->pluck('product.nombre')->implode(', ') }}">
                                        {{ $sale->details->first()->product->nombre ?? 'Venta sin productos' }}
                                        @if($sale->details->count() > 1)
                                            <span class="text-xs font-normal text-gray-500 ml-1">+ {{ $sale->details->count() - 1 }} más</span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-500">Venta #{{ $sale->id }}</p>
                                </div>
                                <span class="flex-shrink-0 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $sale->estado == 'pagada' ? 'bg-green-100 text-green-800' : ($sale->estado == 'cancelada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($sale->estado) }}
                                </span>
                            </div>

                            {{-- Sale Details --}}
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Fecha:</span>
                                    <span class="font-medium text-gray-700">{{ $sale->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Artículos:</span>
                                    <span class="font-medium text-blue-600">{{ $sale->details->sum('cantidad') }} Kg</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Cliente:</span>
                                    <span class="font-medium text-purple-700 truncate">{{ $sale->cliente ?: 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-200 mt-2">
                                    <span class="text-gray-800 font-semibold">Total:</span>
                                    <span class="text-xl font-bold text-green-600">${{ number_format($sale->total, 0) }}</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-end space-x-2 border-t border-gray-100 pt-3">
                                   {{-- Estos botones son visibles para los 3 roles --}}
                                   <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-900 text-xs font-medium" title="Ver Detalle">Ver Detalle</a>
                                   <span class="text-gray-300">|</span>
                                   <a href="{{ route('sales.single.pdf', $sale) }}" target="_blank" class="text-gray-500 hover:text-gray-800 text-xs font-medium" title="Descargar PDF">PDF</a>
                                   
                                   {{-- Botón Cancelar (Solo Admin y Empleado) --}}
                                  @if(in_array(auth()->user()->role->name, ['Administrador', 'Empleado']) && $sale->estado == 'pagada')
                                      <span class="text-gray-300">|</span>
                                      <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline-block form-cancelar-venta">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-medium" title="Cancelar Venta">Cancelar</button>
                                      </form>
                                  @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-8">
                {{ $sales->links() }}
            </div>

            @if($sales->isEmpty())
                <div class="bg-white rounded-xl shadow-lg p-12 text-center mt-6">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay ventas registradas</h3>
                    <p class="text-gray-500 mb-4">Aún no se han realizado ventas o no coinciden con los filtros.</p>
                </div>
            @endif

        </div>
    </div>

{{-- Script de Cancelar Venta --}}
@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const formsCancelar = document.querySelectorAll('.form-cancelar-venta');
    formsCancelar.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); 
            Swal.fire({
                title: '¿Cancelar esta venta?',
                text: "¡Esta acción devolverá el stock al inventario!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', 
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cancelar venta',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush

</x-app-layout>