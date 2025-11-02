<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome'); // <-- NUEVO: Añadido el nombre a la ruta principal

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Productos - SuperAdmin y Admin
    Route::resource('products', ProductController::class)->middleware('role:SuperAdministrador,Administrador');
    // Dentro de routes/web.php
// --- INICIO: RUTA PARA HABILITAR PRODUCTO ---
Route::post('products/{product}/enable', [ProductController::class, 'enable'])
    ->name('products.enable')->middleware('role:Administrador');
// --- FIN: RUTA PARA HABILITAR PRODUCTO ---
    // ... (tus otras rutas) ...

    // --- INICIO: RUTAS DE VENTAS CON PERMISOS CORREGIDOS ---
    
    // Rutas de VISTA y PDF (SuperAdmin, Admin, Empleado)
    Route::middleware('role:SuperAdministrador,Administrador,Empleado')->group(function () {
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('/sales-report/pdf', [SaleController::class, 'generatePDF'])->name('sales.pdf');
        Route::get('/sales/{sale}/pdf', [SaleController::class, 'generateSalePDF'])->name('sales.single.pdf');
    });

    // Rutas de ACCIÓN (Crear, Guardar, Cancelar) (Solo Admin y Empleado)
    Route::middleware('role:Administrador,Empleado')->group(function () {
        Route::get('create.blade.php', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
        Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
    });
    
    // --- FIN: RUTAS DE VENTAS CORREGIDAS ---
    
    // ... (tus rutas de Usuarios, Proveedores, Compras) ...
    // Usuarios - SuperAdmin
    Route::resource('users', UserController::class)->middleware('role:SuperAdministrador');

    Route::post('users/{user}/enable', [UserController::class, 'enable'])->name('users.enable')->middleware('role:SuperAdministrador');

       Route::resource('providers', ProviderController::class)
         ->only(['index', 'show', 'create']) // <-- El SuperAdmin solo accede a estas
         ->middleware('role:SuperAdministrador,Administrador');

    // 2. Rutas de "Gestión" (create, store, edit, update, destroy) SOLO para Admin
    Route::resource('providers', ProviderController::class)
         ->except(['index', 'show']) // <-- Todas MENOS las de "Solo Ver"
         ->middleware('role:Administrador'); // <-- Solo el Administrador

    // Compras (HU1, HU3, HU4, HU5) - Admins pueden todo, SuperAdmin solo ver
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'update', 'destroy'])
            ->middleware('role:SuperAdministrador,Administrador');
    
    // Ruta para anular (HU4) - Solo Admin
    Route::post('purchases/{purchase}/void', [PurchaseController::class, 'void'])
            ->name('purchases.void')->middleware('role:Administrador');
    
        // --- INICIO NUEVA RUTA PDF ---
    // PDF de Compra (SuperAdmin y Admin pueden verlo)
    Route::get('purchases/{purchase}/pdf', [PurchaseController::class, 'generatePDF'])
            ->name('purchases.pdf')->middleware('role:SuperAdministrador,Administrador');
    // --- FIN NUEVA RUTA PDF ---
    });

require __DIR__.'/auth.php';

// --- NUEVO: Añadido para cargar las rutas del módulo de clientes ---
require __DIR__.'/ecommerce_routes.php';

