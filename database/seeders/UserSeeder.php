<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Area;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener roles
        $roleUser = Role::firstOrCreate(['role_name' => 'User']);
        $roleSupport = Role::firstOrCreate(['role_name' => 'Support']);

        // Obtener área Soporte
        $supportArea = Area::firstOrCreate(['area_name' => 'Soporte']);

        // Crear usuario Support fijo
        User::firstOrCreate(
            ['email' => 'andres@gmail.com'],
            [
                'full_name' => 'Andres Carvajal',
                'password' => Hash::make('password123'),
                'role_id' => $roleSupport->id,
                'area_id' => $supportArea->id,
            ]
        );

        // Crear usuario User fijo (asumiendo area_id = 1)
        User::firstOrCreate(
            ['email' => 'alejandro@gmail.com'],
            [
                'full_name' => 'Alejandro Arias',
                'password' => Hash::make('password123'),
                'role_id' => $roleUser->id,
                'area_id' => 1, 
            ]
        );

        // Crear 8 usuarios aleatorios
        User::factory()->count(8)->create();
    }
}
