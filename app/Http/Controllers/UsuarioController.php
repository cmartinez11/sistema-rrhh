<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::with('roles')->orderBy('name')->paginate(10);
        $roles = Role::all();

        return view('configuracion.usuarios.index', compact('usuarios', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->rol);

        return redirect()->route('configuracion.usuarios.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', 'string', 'exists:roles,name'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);
        $usuario->syncRoles([$request->rol]);

        return redirect()->route('configuracion.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        if (Auth::id() === $usuario->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        if ($usuario->hasRole('Administrador')) {
            $adminCount = User::role('Administrador')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'No se puede eliminar el único Administrador del sistema.');
            }
        }

        $usuario->delete();

        return redirect()->route('configuracion.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
