<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesPermissionsSeeder::class);

        $usuarios = [
            [
                'email' => 'superadmin@confipetrol.com',
                'name' => 'Carlos Mendoza',
                'cargo' => 'Administrador del Sistema',
                'area' => 'Tecnología',
                'sede' => 'Bogotá',
                'password' => 'SuperAdmin@2026!',
                'rol' => 'super_admin',
            ],
            [
                'email' => 'admin@confipetrol.com',
                'name' => 'Laura Rodríguez',
                'cargo' => 'Coordinadora de Calidad',
                'area' => 'Calidad y HSE',
                'sede' => 'Bogotá',
                'password' => 'Admin@2026!',
                'rol' => 'admin',
                'roles_do' => ['calidad_corporativa'],
            ],
            [
                'email' => 'gestor@confipetrol.com',
                'name' => 'Andrés Vargas',
                'cargo' => 'Gestor Documental',
                'area' => 'Calidad y HSE',
                'sede' => 'Medellín',
                'password' => 'Gestor@2026!',
                'rol' => 'gestor_documental',
                'roles_do' => ['responsable_hseq'],
            ],
            [
                'email' => 'operativo@confipetrol.com',
                'name' => 'Juan Castaño',
                'cargo' => 'Coordinador de Campo',
                'area' => 'Operaciones',
                'sede' => 'Barrancabermeja',
                'password' => 'Operativo@2026!',
                'rol' => 'operativo',
                'roles_do' => ['lider_om', 'personal_tecnico'],
            ],
        ];

        foreach ($usuarios as $datos) {
            $user = User::firstOrCreate(
                ['email' => $datos['email']],
                [
                    'name' => $datos['name'],
                    'cargo' => $datos['cargo'],
                    'area' => $datos['area'],
                    'sede' => $datos['sede'],
                    'password' => Hash::make($datos['password']),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            if (! $user->hasRole($datos['rol'])) {
                $user->assignRole($datos['rol']);
            }

            foreach ($datos['roles_do'] ?? [] as $rolDo) {
                if (! $user->hasRole($rolDo)) {
                    $user->assignRole($rolDo);
                }
            }
        }

        $this->call(DemoDataSeeder::class);
        $this->call(ActividadesEjemploSeeder::class);
        $this->call(CompromisosDoSeeder::class);
    }
}
