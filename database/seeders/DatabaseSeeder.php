<?php

namespace Database\Seeders;

// use App\Models\AcademicYear;
// use App\Models\Assignment;
// use App\Models\AssignmentSubmission;
// use App\Models\Enrollment;
// use App\Models\Practicum;
// use App\Models\Schedule;
// use App\Models\Shift;
// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            SecurityQuestionSeeder::class,
        ]);

        // Shift::create([
        //     'name' => 'Shift 1',
        // ]);
        // AcademicYear::create([
        //     'year' => '2023/2024',
        //     'semester' => 'ODD',
        //     'status' => 'ACTIVE',
        // ]);
        // Practicum::create([
        //     'academic_year_id' => 1,
        //     'course_id' => 1,
        //     'shift_id' => 1,
        // ]);
        // Enrollment::create([
        //     'user_id' => 1,
        //     'practicum_id' => 1,
        //     'study_plan_path' => 'enrollments/GsDTaJVCyPrG8n1cP5wZ1jIFtJgOohWYdv5SpqMN.pdf',
        //     'transcript_path' => 'enrollments/4fcmU4hk9mGDFodMizrGDgoa97E5Yb9pEls0kf6Q.pdf',
        //     'photo_path' => 'enrollments/x06h70H8zoRWXwWpq0b9X9qQvRkegEtp0pguPh4Z.jpg',
        // ]);
        // Schedule::create([
        //     'practicum_id' => 1,
        //     'meeting_number' => 1,
        //     'topic' => 'Introduction to Practicum',
        //     'date' => now()->addDays(1)->toDateString(),
        //     'start_time' => '09:00:00',
        //     'end_time' => '11:00:00',
        //     'location' => 'Room 101',
        // ]);
        // Assignment::create([
        //     'practicum_id' => 1,
        //     'schedule_id' => 1,
        //     'title' => 'Assignment 1',
        //     'description' => 'This is the first assignment.',
        //     'deadline' => now()->addMinutes(1),
        // ]);
        // AssignmentSubmission::create([
        //     'assignment_id' => 1,
        //     'user_id' => 1,
        //     'file_path' => 'submissions/1/VKefCXno7LxOSyxzWM1VhOLMcL5utj0DhRaf2MKA.pdf',
        //     'is_late' => true,
        // ]);
    }
}
