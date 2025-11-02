<?php

// --- CORRECCIÓN AQUÍ: Cambiado '.' por '\' ---
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Cliente\CustomerAuthController;
// --- PASO 4 ---
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
// --- PASO 5 ---
use App\Http\Controllers\CheckoutController;
// --- PASO 6 ---
use App\Http\Controllers\Admin\AdminOrderController;


/*
|--------------------------------------------------------------------------
| Rutas E-commerce (Clientes)
|--------------------------------------------------------------------------
|
| Estas son las rutas 100% separadas para el flujo de clientes.
| No interfieren con las rutas de auth/ de empleados.
|
*/

// --- PASO 3: AUTENTICACIÓN DE CLIENTES ---

// Grupo de rutas para invitados (clientes no logueados)
Route::middleware('guest')->group(function () {
    // Muestra el formulario de login de clientes
    Route::get('cliente/login', [CustomerAuthController::class, 'showLoginForm'])
            ->name('cliente.login');
    
    // Procesa el formulario de login (Nombre añadido)
    Route::post('cliente/login', [CustomerAuthController::class, 'login'])
            ->name('cliente.login.submit'); // <-- NOMBRE AÑADIDO
    
    // Muestra el formulario de registro de clientes
    Route::get('cliente/register', [CustomerAuthController::class, 'showRegisterForm'])
            ->name('cliente.register');
    
    // Procesa el formulario de registro (Nombre añadido)
    Route::post('cliente/register', [CustomerAuthController::class, 'register'])
            ->name('cliente.register.submit'); // <-- NOMBRE AÑADIDO
});

// --- RUTAS PROTEGIDAS PARA CLIENTES LOGUEADOS ---
// IMPORTANTE: 'auth' asegura que esté logueado, 'role:Cliente' asegura que NO sea un empleado/admin
Route::middleware(['auth', 'role:Cliente'])->group(function () {
    
    // Logout del cliente (movido aquí para que solo clientes logueados puedan hacer logout)
    Route::post('cliente/logout', [CustomerAuthController::class, 'logout'])
            ->name('cliente.logout');

    // --- PASO 4: CATÁLOGO Y CARRITO ---
    // --- CORRECCIÓN AQUÍ: Nombre cambiado a 'catalog.index' ---
    Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index'); 
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    
    // --- PASO 5: CHECKOUT ---
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

}); // Fin del grupo middleware ['auth', 'role:Cliente']


// --- PASO 6: GESTIÓN DE PEDIDOS (ADMIN/EMPLEADO) ---
// Estas rutas deben estar protegidas en tu archivo web.php principal,
// pero las definimos aquí para mantener el módulo organizado.
// Usamos ->middleware('role:Administrador,Empleado') para protegerlas.

Route::middleware(['auth', 'verified', 'role:Administrador,Empleado'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    // Ruta para actualizar estado (tanto despacho como pago)
    Route::post('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus'); 
});

