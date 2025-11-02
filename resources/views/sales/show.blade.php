<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle de la Venta #{{ $sale->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-8 mb-6 border border-gray-100">
                 {{-- Información General --}}
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b">
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">ID Venta</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">#{{ $sale->id }}</p>
                    </div>
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $sale->created_at->format('d/m/Y H:i A') }}</p>
                    </div>
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Registrado por</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $sale->user->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Cliente</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $sale->cliente ?: 'N/A' }}</p>
                    </div>
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Estado</h3>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $sale->estado == 'pagada' ? 'bg-green-100 text-green-800' : ($sale->estado == 'cancelada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($sale->estado) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Venta</h3>
                        <p class="mt-1 text-2xl font-bold text-green-600">${{ number_format($sale->total, 0) }}</p>
                    </div>
                 </div>

                {{-- Información de Pago --}}
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b">
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Método de Pago</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ ucfirst($sale->metodo_pago) }}</p>
                    </div>
                     @if($sale->metodo_pago == 'efectivo')
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Monto Recibido</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($sale->monto_recibido, 0) }}</p>
                    </div>
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Cambio Devuelto</h3>
                        <p class="mt-1 text-lg font-semibold text-blue-600">${{ number_format($sale->cambio, 0) }}</p>
                    </div>
                     @elseif($sale->metodo_pago == 'transferencia')
                     <div class="md:col-span-2"> {{-- Ocupa más espacio --}}
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Referencia Transferencia</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $sale->referencia_transferencia }}</p>
                    </div>
                     @endif
                 </div>
                
                {{-- Tabla de Productos --}}
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Productos Vendidos</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unit.</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sale->details as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->product->nombre ?? 'Producto no encontrado' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $detail->cantidad }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ number_format($detail->precio_unitario, 0) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($detail->subtotal, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                         {{-- Fila del Total General --}}
                         <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-700 uppercase">Total General:</td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($sale->total, 0) }}</td>
                            </tr>
                         </tfoot>
                    </table>
                </div>
            </div> {{-- Fin Tarjeta Blanca --}}
            
            {{-- Botones de Acción --}}
            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('sales.single.pdf', $sale) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                     <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Descargar PDF
                </a>
                <a href="{{ route('sales.index') }}">
                    <x-secondary-button type="button"> 
                        Volver al Historial
                    </x-secondary-button>
                </a>
                 {{-- Botón Cancelar (si aplica) --}}
                 @if(in_array(auth()->user()->role->name, ['SuperAdministrador', 'Administrador']) && $sale->estado == 'pagada')
                    <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline-block form-cancelar-venta">
                        @csrf
                        @method('DELETE')
                         <x-danger-button type="submit" class="ml-2"> {{-- Usamos Danger Button --}}
                            Cancelar Venta
                        </x-danger-button>
                    </form>
                 @endif
            </div>
        </div>
    </div>
    
@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const formsCancelar = document.querySelectorAll('.form-cancelar-venta');
    formsCancelar.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); 
            Swal.fire({ /* ... Configuración de SweetAlert ... */ }).then((result) => {
                if (result.isConfirmed) { this.submit(); }
            });
        });
    });
</script>
@endpush
</x-app-layout>