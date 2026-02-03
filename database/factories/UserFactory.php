<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => $this->faker->randomElement(['student', 'teacher', 'parent']),
            'is_active' => true,
            'is_approved' => true,
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'birth_date' => $this->faker->date(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin()
    {
        return $this->state([
            'role' => 'admin',
            'email' => 'admin@escola.com',
        ]);
    }

    public function secretary()
    {
        return $this->state([
            'role' => 'secretary',
            'email' => 'secretaria@escola.com',
        ]);
    }

    public function teacher()
    {
        return $this->state([
            'role' => 'teacher',
        ]);
    }

    public function student()
    {
        return $this->state([
            'role' => 'student',
        ]);
    }

    public function parent()
    {
        return $this->state([
            'role' => 'parent',
        ]);
    }

    public function unverified()
    {
        return $this->state([
            'email_verified_at' => null,
        ]);
    }
}
