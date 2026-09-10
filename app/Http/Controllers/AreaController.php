<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::withCount('empleados')
            ->orderBy('nombre')
            ->paginate(15);

        return view('configuracion.areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:areas,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ], [
            'nombre.required' => 'El nombre del área es obligatorio.',
            'nombre.unique' => 'Ya existe un área registrada con este nombre.',
        ]);

        Area::create($validated);

        return redirect()->route('configuracion.areas.index')
            ->with('success', 'Área creada exitosamente.');
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('areas', 'nombre')->ignore($area->id)],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ], [
            'nombre.required' => 'El nombre del área es obligatorio.',
            'nombre.unique' => 'Ya existe otra área registrada con este nombre.',
        ]);

        $area->update($validated);

        return redirect()->route('configuracion.areas.index')
            ->with('success', 'Área actualizada exitosamente.');
    }

    public function destroy(Area $area)
    {
        if ($area->empleados()->exists()) {
            return back()->with('error', 'No se puede eliminar el área porque tiene empleados vinculados a ella.');
        }

        $area->delete();

        return redirect()->route('configuracion.areas.index')
            ->with('success', 'Área eliminada correctamente.');
    }
}
