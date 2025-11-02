<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg shadow-lg mb-8">
                <div class="p-8">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">👥 Gestión de Usuarios</h1>
                        <p class="text-purple-100">{{ $users->count() }} usuarios registrados en el sistema</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($users as $user)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($user->role->name == 'SuperAdministrador') bg-red-100 text-red-800
                                    @elseif($user->role->name == 'Administrador') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    @if($user->role->name == 'SuperAdministrador')
                                        👑
                                    @elseif($user->role->name == 'Administrador')
                                        ⚙️
                                    @else
                                        👤
                                    @endif
                                    {{ $user->role->name }}
                                </span>
                            </div>

                            <div class="space-y-2 mb-4 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>ID de Usuario:</span>
                                    <span class="font-mono">#{{ $user->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Registrado:</span>
                                    <span>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Última actividad:</span>
                                    <span>{{ $user->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                           <div class="flex space-x-2">
                            <a href="{{ route('users.edit', $user) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-center transition-colors duration-200 flex items-center justify-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar Rol
                            </a>
                            
                            @if($user->id != auth()->id())
                                @if($user->is_active)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="flex-1 form-deshabilitar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Deshabilitar
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('users.enable', $user) }}" method="POST" class="flex-1 form-habilitar">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
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

            @if($users->isEmpty())
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay usuarios registrados</h3>
                    <p class="text-gray-500">Los usuarios se registrarán automáticamente en el sistema</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📋 Leyenda de Roles</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center p-3 bg-red-50 rounded-lg">
                        <span class="text-2xl mr-3">👑</span>
                        <div>
                            <h4 class="font-semibold text-red-800">SuperAdministrador</h4>
                            <p class="text-sm text-red-600">Acceso completo al sistema</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                        <span class="text-2xl mr-3">⚙️</span>
                        <div>
                            <h4 class="font-semibold text-blue-800">Administrador</h4>
                            <p class="text-sm text-blue-600">Gestión completa excepto usuarios</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-2xl mr-3">👤</span>
                        <div>
                            <h4 class="font-semibold text-green-800">Empleado</h4>
                            <p class="text-sm text-green-600">Solo puede registrar ventas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Capturamos todos los formularios que tengan la clase "form-deshabilitar"
        const formsDeshabilitar = document.querySelectorAll('.form-deshabilitar');
        formsDeshabilitar.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Detenemos el envío normal
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'El usuario será deshabilitado y no podrá ingresar.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Color rojo para el botón de confirmar
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, deshabilitar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    // Si el usuario confirma, enviamos el formulario
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        // Hacemos lo mismo para los formularios de "habilitar"
        const formsHabilitar = document.querySelectorAll('.form-habilitar');
        formsHabilitar.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Detenemos el envío
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'El usuario será habilitado y podrá ingresar al sistema.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754', // Color verde para el botón de confirmar
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, habilitar',
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
</x-app-layout>