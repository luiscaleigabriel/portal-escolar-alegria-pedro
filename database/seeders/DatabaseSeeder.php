<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Criar admin
        User::factory()->admin()->create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        // Criar secretária
        User::factory()->secretary()->create([
            'name' => 'Secretária Maria',
            'email' => 'secretaria@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        // Criar professores
        $teachers = User::factory()->count(10)->teacher()->create();

        // Criar alunos
        $students = User::factory()->count(100)->student()->create();

        // Criar responsáveis
        $parents = User::factory()->count(40)->parent()->create();

        // Criar cursos (turmas)
        $courses = Course::factory()->count(8)->create();

        // Criar disciplinas
        $subjects = Subject::factory()->count(20)->create();

        // Criar posts do blog
        Post::factory()->count(10)->create();

        // Atribuir alunos às turmas
        foreach ($students as $student) {
            $student->coursesAsStudent()->attach(
                $courses->random(),
                ['academic_year' => date('Y'), 'status' => 'active']
            );
        }

        // Atribuir professores às disciplinas e turmas
        foreach ($teachers as $teacher) {
            $coursesToTeach = $courses->random(rand(1, 3));
            foreach ($coursesToTeach as $course) {
                $subjectsToTeach = $subjects->random(rand(2, 4));
                foreach ($subjectsToTeach as $subject) {
                    $course->subjects()->attach($subject, ['teacher_id' => $teacher->id]);
                }
            }
        }

        // Atribuir responsáveis aos alunos
        foreach ($parents as $parent) {
            $studentsToAssign = $students->random(rand(1, 3));
            foreach ($studentsToAssign as $student) {
                $parent->children()->attach($student, [
                    'relationship' => ['father', 'mother', 'guardian'][rand(0, 2)]
                ]);
            }
        }
    }
}
