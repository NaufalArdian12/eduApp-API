<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\GradeLevel;
use Illuminate\Database\Seeder;

class StudentProfileSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Default Student',
                'password' => bcrypt('password'),
                'role' => 'student'
            ]
        );

        $grade = GradeLevel::first();

        StudentProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'grade_level_id' => $grade?->id,
                'onboarding_completed' => true,
            ]
        );
    }
}
