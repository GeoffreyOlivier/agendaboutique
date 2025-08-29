<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les rôles principaux s'ils n'existent pas
        Role::firstOrCreate(['name' => 'shop'], ['guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'craftsman'], ['guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        
        $this->command->info('Rôles créés avec succès !');
    }
}
