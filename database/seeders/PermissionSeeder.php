<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name'=>'launch grades']);
        Permission::create(['name'=>'launch absences']);
        Permission::create(['name'=>'manage students']);
        Permission::create(['name'=>'manage teachers']);
        Permission::create(['name'=>'post news']);
        Permission::create(['name'=>'open chats']);
    }
}
