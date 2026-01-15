<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage students',
            'manage teachers',
            'manage classrooms',
            'view grades',
            'create grades',
            'view absences',
            'send messages'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $student = Role::firstOrCreate(['name' => 'student']);
        $guardian = Role::firstOrCreate(['name' => 'guardian']);

        $admin->givePermissionTo(Permission::all());

        $teacher->givePermissionTo([
            'view dashboard',
            'create grades',
            'view absences',
            'send messages'
        ]);

        $student->givePermissionTo([
            'view dashboard',
            'view grades',
            'view absences',
            'send messages'
        ]);
    }
}
