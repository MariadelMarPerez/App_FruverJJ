<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle de la Compra #{{ $purchase->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-8 mb-6 border border-gray-100">
                 {{-- Información General --}}
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b">
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Proveedor</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $purchase->provider->name }}</p>
                    </div>
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Registrado por</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $purchase->user->name }}</p>
                    </div>
                     <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $purchase->created_at->format('d/m/Y H:i A') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Estado</h3>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $purchase->status == 'anulada' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Costo Total</h3>
                        <p class="mt-1 text-2xl font-bold text-green-600">${{ number_format($purchase->total_cost, 2) }}</p>
                    </div>
                 </div>

                {{-- Tabla de Productos --}}
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Productos Incluidos</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad (Kg)</th> {{-- Añadido Kg --}}
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Costo Unitario (/Kg)</th> {{-- Añadido /Kg --}}
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($purchase->details as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->product->nombre ?? 'Producto no encontrado' }}</td>
                                    {{-- Mostramos cantidad con 2 decimales y 'Kg' --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format(floatval($detail->quantity), 2) }} Kg</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ number_format($detail->cost, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format(floatval($detail->quantity) * floatval($detail->cost), 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                         <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-700 uppercase">Total General:</td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($purchase->total_cost, 2) }}</td>
                            </tr>
                         </tfoot>
                    </table>
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('purchases.pdf', $purchase) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                     <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Descargar PDF
                </a>
                <a href="{{ route('purchases.index') }}">
                    <x-secondary-button type="button">
                        Volver al Historial
                    </x-secondary-button>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
