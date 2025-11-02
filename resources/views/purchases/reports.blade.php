<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de Compras
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Generar Reporte</h3>
                <form action="{{ route('purchases.reports') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700">Desde</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700">Hasta</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Generar</button>
                            <a href="{{ route('purchases.reports') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Limpiar</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Compras Realizadas</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalPurchases }}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Artículos Comprados</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalItems }}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Costo Total</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">${{ number_format($totalCost, 2) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($purchases as $purchase)
                            <tr>
                                <td class="px-6 py-4">{{ $purchase->id }}</td>
                                <td class="px-6 py-4">{{ $purchase->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ $purchase->provider->name }}</td>
                                <td class="px-6 py-4">${{ number_format($purchase->total_cost, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No hay compras en el rango de fechas seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>