{{--
Este es el menú de navegación que se muestra en la parte superior del Catálogo y carrito.
--}}
<nav x-data="{ open: false }" class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo y Nombre de la Tienda -->
            <div class="flex-shrink-0 flex items-center">
                {{-- 
                siempre te lleve de vuelta a la tienda (ya que 'welcome' es para invitados)
                --}}
                <a href="{{ route('catalog.index') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('storage/fce40538-d1bf-4350-8839-560932d3fd09.png') }}" 
                         alt="Logo Fruver Aguacates JJ" 
                         class="w-10 h-10 rounded-full shadow-sm">
                    <span class="text-xl font-bold text-green-700">Fruver Aguacates JJ</span>
                </a>
            </div>

            <!-- Menú de Navegación (Enlaces) -->
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                <a href="{{ route('catalog.index') }}" class="border-green-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    Catálogo
                </a>
                
                {{-- === INICIO DE LA MODIFICACIÓN ( === --}}
                <a href="{{ route('cart.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l.21-2H12.6a1 1 0 00.96-.72l3-6A1 1 0 0015 3H4.852L4.491 1.29A1 1 0 003 1zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                    </svg>
                    Carrito
                    {{-- Mostramos un contador si hay items en el carrito --}}
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="ml-2 bg-green-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>
            
            </div>

            <!-- Menú de Usuario (Derecha) -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <!-- Menú Desplegable -->
                    <div x-show="dropdownOpen" 
                         @click.away="dropdownOpen = false" 
                         x-cloak
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                        
                        {{-- 
                        Este formulario apunta a la ruta de 'cliente.logout' 
                        para cerrar la sesión del CLIENTE (no la del admin).
                        --}}
                        <form method="POST" action="{{ route('cliente.logout') }}">
                            @csrf
                            <a href="{{ route('cliente.logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Cerrar Sesión
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Botón (Móvil) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú Desplegable Móvil -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('catalog.index') }}" class="bg-green-50 border-green-500 text-green-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Catálogo
            </a>
            <a href="{{ route('cart.index') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Carrito
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="ml-2 bg-green-600 text-white text-xs font-bold rounded-full h-5 w-5 inline-flex items-center justify-center">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Opciones de Usuario Móvil -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <!-- Logout Móvil -->
                <form method="POST" action="{{ route('cliente.logout') }}">
                    @csrf
                    <a href="{{ route('cliente.logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        Cerrar Sesión
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>

