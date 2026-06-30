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

        $admin = User::firstOrCreate(
            ['email' => 'admin@confipetrol.com'],
            [
                'name'              => 'Administrador SGD',
                'cargo'             => 'Administrador del Sistema',
                'area'              => 'TI',
                'sede'              => 'Bogotá',
                'password'          => Hash::make('Admin@2026!'),
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );
        $admin->assignRole('super_admin');

        $gestor = User::firstOrCreate(
            ['email' => 'gestor@confipetrol.com'],
            [
                'name'              => 'Gestor Documental',
                'cargo'             => 'Gestor de Documentos',
                'area'              => 'Calidad',
                'sede'              => 'Bogotá',
                'password'          => Hash::make('Gestor@2026!'),
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );
        $gestor->assignRole('gestor_documental');
    }
}
