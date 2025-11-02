<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Asegúrate de que la ruta a tu modelo Role sea correcta
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds..
     * @return void
     */
    public function run()
    {
        
        // Esto protege tus roles de empleado existentes
        Role::firstOrCreate(['name' => 'SuperAdministrador']);
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Empleado']);
        
        
        // Añadimos el rol 'Cliente' 
        Role::firstOrCreate(['name' => 'Cliente']);
        // 
    }
}

