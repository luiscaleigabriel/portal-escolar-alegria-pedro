<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Criar roles
        $admin    = Role::create(['name'=>'admin']);
        $director = Role::create(['name'=>'director']);
        $teacher  = Role::create(['name'=>'teacher']);
        $student  = Role::create(['name'=>'student']);
        $guardian = Role::create(['name'=>'guardian']);

        // Atribuir permissões
        $teacher->givePermissionTo(['launch grades','launch absences','open chats']);

        $director->givePermissionTo(['manage students','manage teachers','post news']);

        $admin->givePermissionTo(Permission::all());
    }
}
