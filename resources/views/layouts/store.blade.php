{{--
Esta es la PLANTILLA MAESTRA para todas las páginas del cliente (Catálogo, Carrito, Checkout).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    <title>@yield('title', 'Fruver Aguacates JJ')</title>

    <!-- Fuentes y Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Pequeño fix para Alpine.js (usado en el menú desplegable) */
        [x-cloak] { display: none !important; }
    </style>
    
    <!-- Alpine.js (para el menú desplegable de usuario) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="antialiased bg-green-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Incluimos el menú de navegación  -->
    @include('components.store-navigation')

    <!-- Contenido Principal -->
    <main class="flex-grow">
        {{-- Aquí es donde estara el contenido de 'catalog/products.blade.php' o 'cart/index.blade.php' --}}
        @yield('content')
    </main>

    <!-- Pie de Página  -->
    <footer class="bg-white text-center py-4 text-sm text-gray-600 border-t mt-auto">
        © {{ date('Y') }} Fruver Aguacates JJ — Ana Velasquez y Maria del Mar Perez.
    </footer>
    
    @stack('scripts')
</body>
</html>
