<?php

namespace App\Http\Controllers;

use App\Models\Boleta;
use App\Models\Empleado;
use App\Models\EnvioBoleta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalEmpleados = Empleado::count();
        $empleadosActivos = Empleado::where('estado', 'activo')->count();
        
        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');

        $boletasMesActual = Boleta::where('periodo_mes', $currentMonth)
            ->where('periodo_anio', $currentYear)
            ->count();

        $boletasPendientes = Boleta::where('estado', 'pendiente')->count();
        $boletasEnviadas = Boleta::where('estado', 'enviada')->count();
        $boletasError = Boleta::where('estado', 'error')->count();

        $ultimosEnvios = EnvioBoleta::with(['boleta.empleado', 'usuario'])
            ->latest('fecha_envio')
            ->take(8)
            ->get();

        return view('dashboard', compact(
            'totalEmpleados',
            'empleadosActivos',
            'boletasMesActual',
            'boletasPendientes',
            'boletasEnviadas',
            'boletasError',
            'ultimosEnvios',
            'currentMonth',
            'currentYear'
        ));
    }
}
