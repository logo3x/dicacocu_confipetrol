<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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
            ],
            [
                'email' => 'gestor@confipetrol.com',
                'name' => 'Andrés Vargas',
                'cargo' => 'Gestor Documental',
                'area' => 'Calidad y HSE',
                'sede' => 'Medellín',
                'password' => 'Gestor@2026!',
                'rol' => 'gestor_documental',
            ],
            [
                'email' => 'revisor@confipetrol.com',
                'name' => 'Patricia Morales',
                'cargo' => 'Ingeniera de Procesos',
                'area' => 'Ingeniería',
                'sede' => 'Cartagena',
                'password' => 'Revisor@2026!',
                'rol' => 'revisor',
            ],
            [
                'email' => 'colaborador@confipetrol.com',
                'name' => 'Diego Pérez',
                'cargo' => 'Técnico Operativo',
                'area' => 'Operaciones',
                'sede' => 'Barrancabermeja',
                'password' => 'Colaborador@2026!',
                'rol' => 'colaborador',
            ],
            [
                'email' => 'consultor@confipetrol.com',
                'name' => 'María Fernández',
                'cargo' => 'Consultora Externa',
                'area' => 'Consultoría',
                'sede' => 'Bogotá',
                'password' => 'Consultor@2026!',
                'rol' => 'consultor',
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
        }
    }
}
