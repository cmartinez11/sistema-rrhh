<?php

namespace App\Policies;

use App\Models\Boleta;
use App\Models\User;

class BoletaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['gestionar-boletas', 'enviar-boletas']);
    }

    public function view(User $user, Boleta $boleta): bool
    {
        return $user->hasAnyPermission(['gestionar-boletas', 'enviar-boletas']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('gestionar-boletas');
    }

    public function delete(User $user, Boleta $boleta): bool
    {
        return $user->hasPermissionTo('gestionar-boletas');
    }

    public function batchSend(User $user): bool
    {
        // SOLO el Jefe de RRHH o Administrador pueden ejecutar el envío masivo
        return $user->hasPermissionTo('enviar-boletas');
    }
}
