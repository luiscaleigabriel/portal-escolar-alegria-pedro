<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@mp10.test',
            'password' => Hash::make('password')
        ]);

        User::create([
            'name' => 'Professor Teste',
            'email' => 'prof@mp10.test',
            'password' => Hash::make('password')
        ]);

        User::create([
            'name' => 'Aluno Teste',
            'email' => 'aluno@mp10.test',
            'password' => Hash::make('password')
        ]);
    }
}
