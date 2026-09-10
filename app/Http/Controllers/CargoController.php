<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CargoController extends Controller
{
    public function index()
    {
        $cargos = Cargo::withCount('empleados')
            ->orderBy('nombre')
            ->paginate(15);

        return view('configuracion.cargos.index', compact('cargos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:cargos,nombre'],
        ], [
            'nombre.required' => 'El nombre del cargo es obligatorio.',
            'nombre.unique' => 'El nombre del cargo ya se encuentra registrado.',
        ]);

        Cargo::create($validated);

        return redirect()->route('configuracion.cargos.index')
            ->with('success', 'Cargo creado exitosamente.');
    }

    public function update(Request $request, Cargo $cargo)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('cargos', 'nombre')->ignore($cargo->id)],
        ], [
            'nombre.required' => 'El nombre del cargo es obligatorio.',
            'nombre.unique' => 'El nombre del cargo ya se encuentra registrado.',
        ]);

        $cargo->update($validated);

        return redirect()->route('configuracion.cargos.index')
            ->with('success', 'Cargo actualizado exitosamente.');
    }

    public function destroy(Cargo $cargo)
    {
        if ($cargo->empleados()->exists()) {
            return back()->with('error', 'No se puede eliminar el cargo porque existen empleados vinculados a él.');
        }

        $cargo->delete();

        return redirect()->route('configuracion.cargos.index')
            ->with('success', 'Cargo eliminado correctamente.');
    }
}
