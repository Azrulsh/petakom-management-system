<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Registration\Student;
use App\Models\Registration\Lecturer;
use App\Models\Election\Committee;

class PetakomUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password1@3');

        // =====================================
        // 1. ADMIN ACCOUNT
        // =====================================

        $admin = User::firstOrNew([
            'email' => 'admin@petakom.test',
        ]);

        $admin->name = 'PETAKOM Administrator';
        $admin->password = $password;
        $admin->role = 'admin';
        $admin->email_verified_at = now();
        $admin->save();


        // =====================================
        // 2. STUDENT ACCOUNT
        // =====================================

        $studentUser = User::firstOrNew([
            'email' => 'student@petakom.test',
        ]);

        $studentUser->name = 'Test Student';
        $studentUser->password = $password;
        $studentUser->role = 'student';
        $studentUser->email_verified_at = now();
        $studentUser->save();


        // Create Student Profile

        $student = Student::firstOrNew([
            'student_number' => 'TEST2026001',
        ]);

        $student->full_name = 'Test Student';
        $student->email = 'student@petakom.test';
        $student->phone = '0123456789';
        $student->programme = 'Bachelor of Computer Science';
        $student->year_of_study = 2;
        $student->status = 'active';
        $student->user_id = $studentUser->id;
        $student->save();

        // =====================================
        // 3. LECTURER ACCOUNT
        // =====================================

        $lecturerUser = User::firstOrNew([
            'email' => 'lecturer@petakom.test',
        ]);

        $lecturerUser->name = 'Test Lecturer';
        $lecturerUser->password = $password;
        $lecturerUser->role = 'lecturer';
        $lecturerUser->status = 'active';
        $lecturerUser->email_verified_at = now();
        $lecturerUser->save();

        // Create Lecturer Registration Record

        $lecturer = Lecturer::firstOrNew([
            'staff_number' => 'STAFF20260012',
        ]);

        $lecturer->user_id = $lecturerUser->id;
        $lecturer->full_name = 'Test Lecturer';
        $lecturer->email = 'lecturer@petakom.test';
        $lecturer->phone = '0123456788';
        $lecturer->department = 'Faculty of Computing';
        $lecturer->status = 'active';
        $lecturer->save();


        // =====================================
        // 4. COORDINATOR ACCOUNT
        // =====================================

        $coordinator = User::firstOrNew([
            'email' => 'coordinator@petakom.test',
        ]);

        $coordinator->name = 'PETAKOM Coordinator';
        $coordinator->password = $password;
        $coordinator->role = 'coordinator';
        $coordinator->email_verified_at = now();
        $coordinator->save();


        // =====================================
        // 5. COMMITTEE SECRETARIAT ACCOUNT
        // =====================================

        $committeeUser = User::firstOrNew([
            'email' => 'committee@petakom.test',
        ]);

        $committeeUser->name = 'Test Committee';
        $committeeUser->password = $password;
        $committeeUser->role = 'student';
        $committeeUser->email_verified_at = now();
        $committeeUser->save();


        // Create Committee Student Profile

        $committeeStudent = Student::firstOrNew([
            'student_number' => 'TEST2026002',
        ]);

        $committeeStudent->full_name = 'Test Committee';
        $committeeStudent->email = 'committee@petakom.test';
        $committeeStudent->phone = '0123456787';
        $committeeStudent->programme = 'Bachelor of Computer Science';
        $committeeStudent->year_of_study = 3;
        $committeeStudent->status = 'active';
        $committeeStudent->user_id = $committeeUser->id;
        $committeeStudent->save();


        // Create Active Committee Membership

        $committee = Committee::firstOrNew([
            'student_id' => $committeeStudent->id,
            'academic_session' => '2026/2027',
        ]);

        $committee->position = 'Committee Secretariat';
        $committee->appointed_at = now()->toDateString();
        $committee->status = 'active';
        $committee->save();


        // =====================================
        // COMPLETED
        // =====================================

        $this->command->info('PETAKOM test users created successfully.');

    }
}
