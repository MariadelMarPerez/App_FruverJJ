<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role; // Importar el modelo Role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered; // Importar para el registro

class CustomerAuthController extends Controller
{
    // Muestra el formulario de login para clientes
    public function showLoginForm()
    {
        return view('cliente.login'); // Carga la vista desde resources/views/cliente/login.blade.php
    }

    // Procesa el intento de login del cliente
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            // Autenticación exitosa

            // Verificar si el usuario tiene el rol 'Cliente'
            $user = Auth::user();

            // Cargar la relación 'role' si no está cargada
            if (!$user->relationLoaded('role')) {
                $user->load('role');
            }

            if ($user->role && $user->role->name === 'Cliente') {
                // Si es Cliente, regenera sesión y redirige al catálogo
                $request->session()->regenerate();
                
                return redirect()->intended(route('catalog.index')); 
            } else {
                // Si es un Admin o Empleado no tiene acceso
                Auth::logout(); // Cerramos la sesión
                return back()->withErrors([
                    'email' => 'Esta cuenta no es una cuenta de cliente. Use el portal de empleados.',
                ])->onlyInput('email');
            }
        }

        // Si las credenciales fallan
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Muestra el formulario de registro para clientes
    public function showRegisterForm()
    {
        return view('cliente.register'); // Carga la vista desde resources/views/cliente/register.blade.php
    }

    // Procesa el intento de registro del cliente
    public function register(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Buscar el ID del rol 'Cliente'
        $clienteRole = Role::where('name', 'Cliente')->first();

        // Verificar si el rol existe
        if (!$clienteRole) {
            // Si no existe, podemos crear un error o loguearlo
            // Por ahora, redirigimos con un error genérico
            return redirect()->route('cliente.register')
                   ->with('error', 'Error interno al registrar. Por favor, intente más tarde.');
        }

        // Crear el nuevo usuario asignando el role_id del Cliente
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $clienteRole->id, // Asignar el ID del rol 'Cliente'
            'is_active' => true, // Activar la cuenta del cliente por defecto
        ]);

        
        event(new Registered($user));

        // Iniciar sesión con el usuario recién creado
        Auth::login($user);

        // Redirigir al catálogo después del registro

        return redirect()->route('catalog.index'); 
    }

    // Cierra la sesión del cliente
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirigir a la página principal después del logout
        return redirect('/'); 
    }
}

