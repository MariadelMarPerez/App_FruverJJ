<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fruver Aguacates JJ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg shadow-lg mb-8">
                <div class="p-8">
                    <h1 class="text-3xl font-bold mb-2">🏪 Fruver Aguacates JJ</h1>
                    <p class="text-green-100">Bienvenido, {{ auth()->user()->name }} - {{ auth()->user()->role->name }}</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Tarjeta Total Productos (Original) --}}
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</h3>
                                <p class="text-gray-600">Total Productos</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta Total Ventas (Original) --}}
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-2xl font-bold text-gray-800">${{ number_format($totalSales, 0) }}</h3>
                                <p class="text-gray-600">Total Ventas (POS)</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta Total Compras (Original) --}}
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-orange-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-orange-100 rounded-full">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 0h12M12 12v8m0 0H4a2 2 0 01-2-2V8a2 2 0 012-2h4m12 8a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-2xl font-bold text-gray-800">${{ number_format($totalPurchases ?? 0, 0) }}</h3>
                                <p class="text-gray-600">Total Compras</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta Stock Bajo (Original) --}}
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-red-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-full">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-2xl font-bold text-gray-800">{{ $lowStockProducts->count() }}</h3>
                                <p class="text-gray-600">Stock Bajo</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{-- Fin del primer grid --}}

            {{-- --- --- --}}
            {{-- Módulo de Pedidos de Clientes  --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                 <h3 class="text-xl font-bold text-gray-800 mb-4">Módulo de Pedidos de Clientes</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NUEVA Tarjeta Pedidos por Despachar --}}
                    <div class="bg-white rounded-xl shadow-inner border border-gray-200"> {{-- Estilo interno --}}
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-100 rounded-full">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalPedidosPendientes }}</h3>
                                    <p class="text-gray-600">Pedidos por Despachar</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta Ingresos Web --}}
                    <div class="bg-white rounded-xl shadow-inner border border-gray-200"> {{-- Estilo interno --}}
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-indigo-100 rounded-full">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-800">${{ number_format($ingresosPorPedidos, 0) }}</h3>
                                    <p class="text-gray-600">Ingresos (Pedidos Web)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
            


            <!-- Low Stock Alert (Original) -->
            @if($lowStockProducts->count() > 0)
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-800">⚠️ Productos con Stock Bajo</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($lowStockProducts as $product)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $product->nombre }}</h4>
                                        <p class="text-sm text-gray-600">Stock actual: <span class="font-bold text-red-600">{{ number_format($product->stock, 2) }} Kg</span></p>
                                    </div>
                                    @if(in_array(auth()->user()->role->name, ['SuperAdministrador', 'Administrador']))
                                        <a href="{{ route('purchases.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm transition-colors duration-200">
                                            Reponer
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🚀 Acciones Rápidas</h3>

                @php
                    $role = auth()->user()->role->name;
                    $buttonCount = 0;
                    if ($role == 'Administrador') $buttonCount = 5; // Prod, Compra, Prov, VentaPOS, Pedidos
                    elseif ($role == 'Empleado') $buttonCount = 2; // VentaPOS, Pedidos
                    elseif ($role == 'SuperAdministrador') $buttonCount = 6; // Ver Prod, Ver Prov, Ver Compras, Ver Ventas, Gest Users, Gest Pedidos

                    $gridCols = 'md:grid-cols-1';
                    if ($buttonCount == 5) $gridCols = 'md:grid-cols-3 lg:grid-cols-5';
                    if ($buttonCount == 6) $gridCols = 'md:grid-cols-3 lg:grid-cols-6';
                @endphp
                <div class="grid grid-cols-1 {{ $gridCols }} gap-4">

                    {{-- Botones originales (sin cambios) --}}
                    @if($role == 'Administrador')
                        <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span class="font-semibold">Agregar Producto</span>
                        </a>
                        <a href="{{ route('purchases.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 0h12M12 12v8m0 0H4a2 2 0 01-2-2V8a2 2 0 012-2h4m12 8a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="font-semibold">Registrar Compra</span>
                        </a>
                        <a href="{{ route('providers.create') }}" class="bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m6-4h1m-1 4h1m-1 4h1" /></svg>
                            <span class="font-semibold">Registrar Proveedor</span>
                        </a>
                    @endif
                    @if(in_array($role, ['Administrador', 'Empleado']))
                        <a href="{{ route('sales.create') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span class="font-semibold">Registrar Venta (POS)</span>
                        </a>
                    @endif

                    {{-- Botón Gestionar Pedidos (Verificado) --}}
                    @if(in_array($role, ['Administrador', 'Empleado']))
                        <a href="{{ route('admin.orders.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <span class="font-semibold">Gestionar Pedidos</span>
                        </a>
                    @endif

                    {{-- Botones originales Super Admin (sin cambios) --}}
                    @if($role == 'SuperAdministrador')
                        <a href="{{ route('products.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            <span class="font-semibold">Ver Productos</span>
                        </a>
                        <a href="{{ route('providers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                             <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m6-4h1m-1 4h1m-1 4h1" /></svg>
                            <span class="font-semibold">Ver Proveedores</span>
                        </a>
                        <a href="{{ route('purchases.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <span class="font-semibold">Ver Compras</span>
                        </a>
                        <a href="{{ route('sales.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                             <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="font-semibold">Ver Ventas (POS)</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                            <span class="font-semibold">Gestionar Usuarios</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

