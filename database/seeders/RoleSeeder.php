<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Roles
        $admin = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        $director = Role::firstOrCreate([
            'name'       => 'director',
            'guard_name' => 'web',
        ]);

        $teacher = Role::firstOrCreate([
            'name'       => 'teacher',
            'guard_name' => 'web',
        ]);

        $student = Role::firstOrCreate([
            'name'       => 'student',
            'guard_name' => 'web',
        ]);

        $guardian = Role::firstOrCreate([
            'name'       => 'guardian',
            'guard_name' => 'web',
        ]);

        // Permissions
        $teacher->givePermissionTo([
            'create grades',
            'create absences',
            'send messages',
        ]);

        $director->givePermissionTo([
            'manage students',
            'manage teachers',
            'post news',
        ]);

        // Admin tem tudo
        $admin->givePermissionTo(Permission::all());
    }
}
