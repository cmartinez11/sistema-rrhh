<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Boleta;
use App\Models\Cargo;
use App\Models\Configuracion;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            EstructuraEmpresaSeeder::class,
            CargoSeeder::class,
        ]);

        // 1. Usuarios con roles
        $admin = User::firstOrCreate(
            ['email' => 'admin@fenix.com'],
            [
                'name' => 'Administrador Sistema',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('Administrador');

        $jefe = User::firstOrCreate(
            ['email' => 'jefe.rrhh@fenix.com'],
            [
                'name' => 'Carlos Mendoza (Jefe RRHH)',
                'password' => Hash::make('password'),
            ]
        );
        $jefe->assignRole('Jefe de RRHH');

        $asistente = User::firstOrCreate(
            ['email' => 'asistente.rrhh@fenix.com'],
            [
                'name' => 'Laura Torres (Asistente RRHH)',
                'password' => Hash::make('password'),
            ]
        );
        $asistente->assignRole('Asistente de RRHH');

        // 2. Áreas y Cargos de Plásticos Fénix
        $areaProd = Area::create([
            'nombre' => 'Producción',
            'descripcion' => 'Plantas de inyección, soplado y extrusión de plásticos',
        ]);

        $areaLog = Area::create([
            'nombre' => 'Logística y Almacén',
            'descripcion' => 'Almacén de materia prima, producto terminado y despachos',
        ]);

        $areaRRHH = Area::create([
            'nombre' => 'Recursos Humanos',
            'descripcion' => 'Gestión de personal, planillas y bienestar social',
        ]);

        $areaFin = Area::create([
            'nombre' => 'Contabilidad y Finanzas',
            'descripcion' => 'Facturación, gestión tesorería y costos',
        ]);

        $areaCal = Area::create([
            'nombre' => 'Control de Calidad',
            'descripcion' => 'Inspección de calidad y certificaciones ISO',
        ]);

        // Cargos
        $cargoOperario = Cargo::create(['nombre' => 'Operario de Inyección', 'area_id' => $areaProd->id]);
        $cargoJefePlanta = Cargo::create(['nombre' => 'Jefe de Planta', 'area_id' => $areaProd->id]);
        $cargoMontacarga = Cargo::create(['nombre' => 'Operador de Montacargas', 'area_id' => $areaLog->id]);
        $cargoJefeRRHH = Cargo::create(['nombre' => 'Jefe de Recursos Humanos', 'area_id' => $areaRRHH->id]);
        $cargoAsistenteRRHH = Cargo::create(['nombre' => 'Asistente de Recursos Humanos', 'area_id' => $areaRRHH->id]);
        $cargoAnalistaContable = Cargo::create(['nombre' => 'Analista Contable', 'area_id' => $areaFin->id]);
        $cargoInspectorCalidad = Cargo::create(['nombre' => 'Inspector de Calidad', 'area_id' => $areaCal->id]);

        // 3. Empleados de prueba
        $empleadosData = [
            [
                'nombres' => 'Juan Alberto',
                'apellidos' => 'Pérez Gómez',
                'dni' => '12345678',
                'cargo_id' => $cargoOperario->id,
                'area_id' => $areaProd->id,
                'fecha_ingreso' => '2023-01-15',
                'estado' => 'activo',
                'email' => 'juan.perez@example.com',
                'telefono' => '987654321',
            ],
            [
                'nombres' => 'María Elena',
                'apellidos' => 'Gómez Silva',
                'dni' => '87654321',
                'cargo_id' => $cargoMontacarga->id,
                'area_id' => $areaLog->id,
                'fecha_ingreso' => '2022-05-10',
                'estado' => 'activo',
                'email' => 'maria.gomez@example.com',
                'telefono' => '912345678',
            ],
            [
                'nombres' => 'Carlos Andrés',
                'apellidos' => 'Rodríguez Castro',
                'dni' => '11223344',
                'cargo_id' => $cargoInspectorCalidad->id,
                'area_id' => $areaCal->id,
                'fecha_ingreso' => '2024-02-01',
                'estado' => 'activo',
                'email' => 'carlos.rodriguez@example.com',
                'telefono' => '955443322',
            ],
            [
                'nombres' => 'Ana Sofia',
                'apellidos' => 'Martínez Vargas',
                'dni' => '44332211',
                'cargo_id' => $cargoAnalistaContable->id,
                'area_id' => $areaFin->id,
                'fecha_ingreso' => '2021-11-20',
                'estado' => 'activo',
                'email' => 'ana.martinez@example.com',
                'telefono' => '944332211',
            ],
            [
                'nombres' => 'Roberto Carlos',
                'apellidos' => 'Díaz Morales',
                'dni' => '55667788',
                'cargo_id' => $cargoOperario->id,
                'area_id' => $areaProd->id,
                'fecha_ingreso' => '2020-08-12',
                'estado' => 'inactivo',
                'email' => 'roberto.diaz@example.com',
                'telefono' => '966778899',
            ],
        ];

        $empleados = [];
        foreach ($empleadosData as $empData) {
            $empleados[] = Empleado::create($empData);
        }

        // 4. Configuraciones del sistema por defecto
        Configuracion::establecer('mail_plantilla_asunto', 'Boleta de Pago - {periodo} - PLASTICOS FENIX', 'Asunto por defecto de correos de boletas');
        Configuracion::establecer('mail_plantilla_cuerpo', "<p>Estimado(a) <strong>{nombre}</strong>,</p>\n<p>Adjunto a este correo encontrará su <strong>Boleta de Pago correspondiente a {periodo}</strong>.</p>\n<p>Si tiene alguna consulta referente a su boleta, por favor comuníquese con el departamento de Recursos Humanos.</p>\n<br>\n<p>Atentamente,</p>\n<p><strong>Área de Recursos Humanos</strong><br>PLASTICOS FENIX</p>", 'Cuerpo HTML por defecto de correos de boletas');
        Configuracion::establecer('mail_remitente_nombre', 'PLASTICOS FENIX - RRHH', 'Nombre del remitente');
        Configuracion::establecer('mail_remitente_email', 'boletas@plasticosfenix.com', 'Correo del remitente');
        Configuracion::establecer('ruta_compartida', config('filesystems.disks.boletas.root'), 'Ruta base de la carpeta compartida de boletas');

        // 5. Boletas de prueba y generación de PDF simulado
        $periodoMes = (int) date('n');
        $periodoAnio = (int) date('Y');

        foreach ($empleados as $idx => $emp) {
            if ($emp->estado !== 'activo') continue;

            $relPath = "boletas/{$periodoAnio}/" . str_pad($periodoMes, 2, '0', STR_PAD_LEFT) . "/{$emp->dni}.pdf";

            // Crear un PDF de muestra minimalista pero válido
            $pdfContent = "%PDF-1.4\n1 0 obj<>/ProcSet[/PDF/Text]/ExtGState<>>>/Type/Page>>endobj 2 0 obj<>/ColorSpace<>>>/Type/Pages>>endobj 3 0 obj<>/Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj 4 0 obj<>/Length 175>>stream\nBT /F1 14 Tf 50 750 Td (PLASTICOS FENIX) Tj ET\nBT /F1 12 Tf 50 720 Td (BOLETA DE PAGO - MES: {$periodoMes}/{$periodoAnio}) Tj ET\nBT /F1 10 Tf 50 690 Td (Empleado: {$emp->nombre_completo}) Tj ET\nBT /F1 10 Tf 50 670 Td (DNI: {$emp->dni}) Tj ET\nBT /F1 10 Tf 50 650 Td (Cargo: {$emp->cargo->nombre}) Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f\n0000000009 00000 n\n0000000140 00000 n\n0000000244 00000 n\n0000000344 00000 n\ntrailer<>/Root 1 0 R>>\nstartxref\n570\n%%EOF";

            Storage::disk('boletas')->put($relPath, $pdfContent);

            $estado = ($idx === 0) ? 'enviada' : 'pendiente';

            Boleta::create([
                'empleado_id' => $emp->id,
                'periodo_mes' => $periodoMes,
                'periodo_anio' => $periodoAnio,
                'ruta_pdf' => $relPath,
                'estado' => $estado,
                'created_by' => $asistente->id,
            ]);
        }
    }
}
