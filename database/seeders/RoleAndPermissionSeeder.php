<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear Permisos
        $permissions = [
            'gestionar-empleados',
            'gestionar-boletas',
            'enviar-boletas',
            'ver-auditoria',
            'gestionar-configuracion',
            'gestionar-contratos',
            'enviar-contratos',
            'gestionar-documentos',
            'enviar-documentos',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Crear Roles y Asignar Permisos
        $adminRole = Role::findOrCreate('Administrador', 'web');
        $adminRole->syncPermissions(Permission::all());

        $jefeRole = Role::findOrCreate('Jefe de RRHH', 'web');
        $jefeRole->syncPermissions([
            'gestionar-empleados',
            'gestionar-boletas',
            'enviar-boletas',
            'ver-auditoria',
            'gestionar-configuracion',
            'gestionar-contratos',
            'enviar-contratos',
            'gestionar-documentos',
            'enviar-documentos',
        ]);

        $asistenteRole = Role::findOrCreate('Asistente de RRHH', 'web');
        $asistenteRole->syncPermissions([
            'gestionar-empleados',
            'gestionar-boletas',
            'ver-auditoria',
        ]);
    }
}
