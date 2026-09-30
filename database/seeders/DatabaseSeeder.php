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
        $this->call(DoPermissionsSeeder::class);

        $usuarios = [
            [
                'email' => 'superadmin@confipetrol.com',
                'name' => 'Carlos Mendoza',
                'password' => 'SuperAdmin@2026!',
                'rol' => 'super_admin',
            ],
            [
                'email' => 'admin@confipetrol.com',
                'name' => 'Laura Rodríguez',
                'password' => 'Admin@2026!',
                'rol' => 'admin',
                'roles_do' => ['calidad_corporativa'],
            ],
            [
                'email' => 'gestor@confipetrol.com',
                'name' => 'Andrés Vargas',
                'password' => 'Gestor@2026!',
                'rol' => 'responsable_hseq',
            ],
            [
                'email' => 'operativo@confipetrol.com',
                'name' => 'Juan Castaño',
                'password' => 'Operativo@2026!',
                'rol' => 'personal_tecnico',
            ],
        ];

        foreach ($usuarios as $datos) {
            $user = User::firstOrCreate(
                ['email' => $datos['email']],
                [
                    'name' => $datos['name'],
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
