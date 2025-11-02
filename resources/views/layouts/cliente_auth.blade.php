<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fruver Aguacates JJ') }} - Acceso Clientes</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased bg-green-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Encabezado simple para login/registro -->
    <header class="py-4 bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center">

            {{-- Aumentamos tamaño de logo y texto --}}
            <a href="{{ route('welcome') }}" class="flex items-center space-x-3 group">
                <img src="{{ asset('storage/fce40538-d1bf-4350-8839-560932d3fd09.png') }}"
                     alt="Logo Fruver Aguacates JJ"
                     class="w-12 h-12 rounded-full shadow-sm transition-transform duration-300 group-hover:scale-110"> {{-- Cambiado w-10 h-10 por w-12 h-12 --}}
                <span class="text-2xl font-bold text-green-700 transition-colors duration-300 group-hover:text-green-800">Fruver Aguacates JJ</span> {{-- Cambiado text-xl por text-2xl --}}
            </a>
            
        </div>
    </header>

    <!--  (Formulario Login o Registro) -->
    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            {{-- Aquí se insertará el contenido de las vistas login.blade.php o register.blade.php --}}
            @yield('content')
        </div>
    </main>

    <!-- Pie de Página -->
    <footer class="bg-white text-center py-4 text-sm text-gray-600 border-t mt-auto">
        © {{ date('Y') }} Fruver Aguacates JJ — Ana Velasquez y Maria del Mar Perez.
    </footer>

</body>
</html>

