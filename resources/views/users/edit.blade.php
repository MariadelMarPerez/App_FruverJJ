<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg shadow-lg mb-8">
                <div class="p-8">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <div>
                            <h1 class="text-3xl font-bold">Editar Usuario</h1>
                            <p class="text-indigo-100">Modificar el rol de {{ $user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Info Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <p class="text-sm text-gray-500">Registrado: {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Current Role Display -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Rol Actual</h4>
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

                        <!-- Role Selection -->
                        <div>
                            <label for="role_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Nuevo Rol *
                                </span>
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200" id="role_id" name="role_id" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                        @if($role->name == 'SuperAdministrador')
                                            👑
                                        @elseif($role->name == 'Administrador')
                                            ⚙️
                                        @else
                                            👤
                                        @endif
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role Descriptions -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-blue-800 font-semibold mb-3">📋 Descripción de Roles</h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <span class="text-red-500 mr-2">👑</span>
                                    <div>
                                        <h5 class="font-semibold text-red-800">SuperAdministrador</h5>
                                        <p class="text-sm text-red-700">Acceso completo a todas las funciones del sistema, incluyendo gestión de usuarios.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-blue-500 mr-2">⚙️</span>
                                    <div>
                                        <h5 class="font-semibold text-blue-800">Administrador</h5>
                                        <p class="text-sm text-blue-700">Puede gestionar productos y ventas, pero no tiene acceso a la gestión de usuarios.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-green-500 mr-2">👤</span>
                                    <div>
                                        <h5 class="font-semibold text-green-800">Empleado</h5>
                                        <p class="text-sm text-green-700">Solo puede registrar ventas y ver información básica del sistema.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Actualizar Rol
                            </button>
                            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-200 text-center flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <h4 class="text-yellow-800 font-semibold mb-2">⚠️ Importante</h4>
                        <ul class="text-yellow-700 text-sm space-y-1">
                            <li>• Cambiar el rol de un usuario afectará sus permisos inmediatamente</li>
                            <li>• Asegúrate de que el usuario sepa cómo usar su nuevo rol</li>
                            <li>• Los SuperAdministradores tienen acceso completo al sistema</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>