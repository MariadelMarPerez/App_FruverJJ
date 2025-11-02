<x-app-layout>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ===== BANNER ===== --}}
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg shadow-lg mb-8">
                <div class="p-6 flex justify-between items-center">
                    {{-- Lado izquierdo: Título y contador --}}
                    <div>
                        <h1 class="text-3xl font-bold mb-1">🚚 Gestión de Proveedores</h1>
                        <p class="text-blue-100">{{ $providers->count() }} proveedores registrados</p>
                    </div>
                    {{-- Lado derecho: Botón "+ Nuevo Proveedor" --}}
                    <div>
                        {{-- Solo el Administrador puede crear --}}
                        @if(auth()->user()->role->name == 'Administrador')
                        <a href="{{ route('providers.create') }}" class="bg-white hover:bg-gray-50 text-blue-700 font-bold py-2 px-4 rounded shadow transition-colors duration-200">
                            + Nuevo Proveedor
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            {{-- ===== FIN: BANNER ===== --}}

            {{-- Tabla de proveedores  --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teléfono</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($providers as $provider)
                            <tr class="hover:bg-gray-50 {{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $provider->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $provider->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $provider->phone ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    {{-- Solo el Administrador puede Editar o Eliminar --}}
                                    @if(auth()->user()->role->name == 'Administrador')
                                        <a href="{{ route('providers.edit', $provider) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Editar
                                        </a>
                                        <form action="{{ route('providers.destroy', $provider) }}" method="POST" class="inline-block form-eliminar-provider">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Eliminar
                                            </button>
                                        </form>
                                    @else
                                        {{-- El Super Admin no tiene acciones --}}
                                        <span class="text-gray-400 text-xs italic">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    No hay proveedores registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

{{-- Script de SweetAlert (este ya estaba) --}}
@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const deleteForms = document.querySelectorAll('.form-eliminar-provider');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); 
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto! Solo se eliminará si no tiene compras asociadas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
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