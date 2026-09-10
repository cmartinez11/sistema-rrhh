<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpleadoRequest;
use App\Http\Requests\UpdateEmpleadoRequest;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $query = Empleado::with(['area', 'cargo']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->input('area_id'));
        }

        if ($request->filled('cargo_id')) {
            $query->where('cargo_id', $request->input('cargo_id'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        $empleados = $query->orderBy('apellidos')->paginate(10)->withQueryString();

        $areas = Area::orderBy('nombre')->get();
        $cargos = Cargo::orderBy('nombre')->get();

        return view('empleados.index', compact('empleados', 'areas', 'cargos'));
    }

    public function create()
    {
        $areas = Area::orderBy('nombre')->get();
        $cargos = Cargo::orderBy('nombre')->get();
        return view('empleados.create', compact('areas', 'cargos'));
    }

    public function store(StoreEmpleadoRequest $request)
    {
        Empleado::create($request->validated());

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado registrado exitosamente.');
    }

    public function edit(Empleado $empleado)
    {
        $areas = Area::orderBy('nombre')->get();
        $cargos = Cargo::orderBy('nombre')->get();
        return view('empleados.edit', compact('empleado', 'areas', 'cargos'));
    }

    public function update(UpdateEmpleadoRequest $request, Empleado $empleado)
    {
        $empleado->update($request->validated());

        return redirect()->route('empleados.index')
            ->with('success', 'Datos del empleado actualizados exitosamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }

    public function getCargosPorArea(Area $area)
    {
        return response()->json(Cargo::orderBy('nombre')->get());
    }
}
