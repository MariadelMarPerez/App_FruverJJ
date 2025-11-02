<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg shadow-lg mb-8">
                <div class="p-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-1">📦 Gestión de Productos</h1>
                        {{-- $products->total() da el conteo total de la consulta paginada --}}
                        <p class="text-blue-100">{{ $products->total() }} productos encontrados</p>
                    </div>
                    @if(in_array(auth()->user()->role->name, ['Administrador']))
                        <a href="{{ route('products.create') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 text-sm transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Nuevo Producto
                        </a>
                    @endif
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filtros y Ordenamiento
                </h3>
                <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4"> {{-- Ajustado a 4 columnas --}}
                    {{-- Búsqueda por Nombre --}}
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar por nombre</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" placeholder="Nombre del producto...">
                    </div>
                    {{-- Filtro Stock --}}
                    <div>
                        <label for="stock_filter" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                        <select name="stock_filter" id="stock_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            <option value="">Todos</option>
                            <option value="available" {{ request('stock_filter') == 'available' ? 'selected' : '' }}>Con Stock (>0 Kg)</option>
                            <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Stock Bajo (≤10 Kg)</option>
                            <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Sin Stock (0 Kg)</option>
                        </select>
                    </div>
                     {{-- Filtro Estado --}}
                    <div>
                        <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="status_filter" id="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        
                            <option value="all" {{ request('status_filter', 'all') == 'all' ? 'selected' : '' }}>Todos</option>
                            <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Activos</option>
                            <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    {{-- Botones (Ocupan la siguiente fila en móvil, o se alinean al final en PC) --}}
                    <div class="flex items-end space-x-2 md:col-span-4 md:justify-end"> {{-- Ocupa 4 columnas y alinea a la derecha --}}
                        <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Filtrar
                        </button>
                        <a href="{{ route('products.index') }}" class="w-full md:w-auto bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm" title="Limpiar filtros">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Products Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 {{ !$product->is_active ? 'opacity-50 bg-gray-100' : '' }}">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1 mr-4">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->nombre }}</h3>
                                    @if($product->descripcion)
                                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->descripcion, 80) }}</p>
                                    @endif
                                </div>
                                <span class="flex-shrink-0 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full whitespace-nowrap">
                                    ID: {{ $product->id }}
                                </span>
                            </div>

                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Precio (/Kg):</span>
                                    <span class="text-2xl font-bold text-green-600">${{ number_format($product->precio, 0) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Stock (Kg):</span>
                                    <span class="text-lg font-semibold {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-orange-600' : 'text-red-600') }}">
                                        {{ number_format(floatval($product->stock), 2) }} Kg
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                    <span class="text-gray-600">Estado:</span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                @if(in_array(auth()->user()->role->name, [ 'Administrador']))
                                <a href="{{ route('products.edit', $product) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-center transition-colors duration-200 flex items-center justify-center text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Editar
                                </a>
                                @endif
                                
                                @if(in_array(auth()->user()->role->name, ['Administrador']))
                                    @if($product->is_active)
                                        {{-- Botón Deshabilitar --}}
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="flex-1 form-deshabilitar-producto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Deshabilitar
                                            </button>
                                        </form>
                                    @else
                                        {{-- Botón Habilitar --}}
                                        <form action="{{ route('products.enable', $product) }}" method="POST" class="flex-1 form-habilitar-producto">
                                            @csrf
                                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm">
                                                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Habilitar
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Mensaje si no hay productos --}}
            @if($products->isEmpty())
                <div class="bg-white rounded-xl shadow-lg p-12 text-center mt-6">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No se encontraron productos</h3>
                    <p class="text-gray-500">Intenta ajustar los filtros o agrega nuevos productos.</p>
                </div>
            @endif

            {{-- ===== PAGINACIÓN ===== --}}
            <div class="mt-8">
                 {{ $products->links() }}
            </div>
            {{-- ===== FIN PAGINACIÓN ===== --}}

        </div>
    </div>


@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Confirmación para deshabilitar
    const formsDeshabilitar = document.querySelectorAll('.form-deshabilitar-producto');
    formsDeshabilitar.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); 
            Swal.fire({
                title: '¿Deshabilitar producto?',
                text: "El producto no aparecerá en ventas ni compras.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', 
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, deshabilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Confirmación para habilitar
    const formsHabilitar = document.querySelectorAll('.form-habilitar-producto');
    formsHabilitar.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); 
            Swal.fire({
                title: '¿Habilitar producto?',
                text: "El producto volverá a estar disponible.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754', // Verde 
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, habilitar',
                cancelButtonText: 'Cancelar'
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
