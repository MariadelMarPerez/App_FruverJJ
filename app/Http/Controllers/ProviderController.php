<?php
namespace App\Http\Controllers;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::all();
        return view('providers.index', compact('providers'));
    }

    public function create()
    {
        return view('providers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:providers',
            'phone' => 'nullable|string|max:20',
        ]);

        Provider::create($request->all());
        return redirect()->route('providers.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Provider $provider)
    {
        // No se usa generalmente, redirigimos a editar
        return redirect()->route('providers.show', $provider);
    }

    public function edit(Provider $provider)
    {
        return view('providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:providers,name,' . $provider->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $provider->update($request->all());
        return redirect()->route('providers.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Provider $provider)
    {
        // Prevenir borrado si tiene compras asociadas
        if ($provider->purchases()->count() > 0) {
            return redirect()->route('providers.index')->with('error', 'No se puede eliminar un proveedor con compras registradas.');
        }

        $provider->delete();
        return redirect()->route('providers.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}