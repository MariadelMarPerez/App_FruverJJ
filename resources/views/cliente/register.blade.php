<!--
Esta es la VISTA DE REGISTRO para CLIENTES.
También utiliza la plantilla 'cliente_auth'.
-->
@extends('layouts.cliente_auth')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-3xl font-bold text-center text-green-700 mb-6">
        Crear Cuenta de Cliente
    </h2>

    <!-- Muestra errores de validación -->
    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
            <p class="font-bold">¡Ups! Algo salió mal.</p>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario de Registro -->
    <form method="POST" action="{{ route('cliente.register.submit') }}">
        @csrf

        <!-- Nombre -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
            <input id="name" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                   type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
            <input id="email" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                   type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input id="password" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                   type="password" name="password" required autocomplete="new-password" />
        </div>

        <!-- Confirmar Contraseña -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
            <input id="password_confirmation" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                   type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <!-- Botón de Registro -->
        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                Registrarme
            </button>
        </div>
    </form>

    <!-- Enlace a Login -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            ¿Ya tienes una cuenta?
            <a href="{{ route('cliente.login') }}" class="font-medium text-green-600 hover:text-green-500">
                Ingresa aquí
            </a>
        </p>
    </div>
</div>
@endsection
