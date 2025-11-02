<!--
Esta es la VISTA DE LOGIN para CLIENTES.
-->
@extends('layouts.cliente_auth')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-3xl font-bold text-center text-green-700 mb-6">
        Ingreso de Clientes
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

    <!-- Formulario de Login -->
    <form method="POST" action="{{ route('cliente.login.submit') }}">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
            <input id="email" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input id="password" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                   type="password" name="password" required autocomplete="current-password" />
        </div>

        <!-- Botón de Ingreso -->
        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                Ingresar
            </button>
        </div>
    </form>

    <!-- Enlace a Registro (Tu requisito de "crear usuario") -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            ¿No tienes una cuenta?
            <a href="{{ route('cliente.register') }}" class="font-medium text-green-600 hover:text-green-500">
                Regístrate aquí
            </a>
        </p>
    </div>
</div>
@endsection
