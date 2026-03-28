<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos de Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear permisos de prueba
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit users']);

        // 2. Crear Roles
        $roleSuperAdmin = Role::create(['name' => 'Superadministrador']);
        $roleAdmin = Role::create(['name' => 'Administrador']);

        // Asignar permisos bases al Administrador
        // El Superadministrador no lo requiere explícitamente pero se lo asignamos todo por comodidad
        $roleAdmin->givePermissionTo(['view dashboard', 'view users']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // 3. Crear Usuarios Maestros
        $superadmin = User::create([
            'name' => 'Superadmin Principal',
            'email' => 'superadmin@larastack.com',
            'password' => Hash::make('password'),
        ]);
        $superadmin->assignRole($roleSuperAdmin);

        $admin = User::create([
            'name' => 'Admin de Sistema',
            'email' => 'admin@larastack.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($roleAdmin);
    }
}
