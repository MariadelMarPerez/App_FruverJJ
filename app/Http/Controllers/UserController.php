<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $users = User::with('role')->get(); // Así debe estar
    return view('users.index', compact('users'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only('role_id'));

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // --- CAMBIO PRINCIPAL ---
        $user->update(['is_active' => false]);
        // --- FIN DEL CAMBIO ---

        // Mensaje de éxito actualizado
        return redirect()->route('users.index')->with('success', 'Usuario deshabilitado exitosamente.');
    }

    /**
     * Habilita un usuario que estaba deshabilitado.
     */
    public function enable(string $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);

        return redirect()->route('users.index')->with('success', 'Usuario habilitado exitosamente.');
    }
}
