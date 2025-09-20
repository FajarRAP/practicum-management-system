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
        $roles = ['student', 'assistant', 'lecturer', 'programming_lecturer', 'apsi_lecturer', 'lab_tech'];
        $viewPermissions = [
            'academic_year.view',
            'archive.view',
            'assignment.view',
            'practicum.view',
            'shift.view',
            'student_datas.view',
            'students.view',
            'submission.view',
        ];
        $permissions = [
            // assistant
            'academic_year.view',
            'archive.view',
            'assignment.view',
            'practicum.view',
            'shift.view',
            'student_datas.view',
            'students.view',
            'submission.view',
            'academic_year.add',
            'academic_year.edit',
            'academic_year.delete',
            'shift.add',
            'shift.edit',
            'shift.delete',
            'practicum.add',
            'practicum.delete',
            'archive.add',
            'archive.delete',
            'schedule.add',
            'schedule.edit',
            'schedule.delete',
            'manage_records',
            'assignment.add',
            'assignment.edit',
            'assignment.delete',
            'scores.calculate',
            // lab tech
            'schedule.approve',
            'manage_assistant_attendance',
            // student
            'practicum.enroll',
            'practicum.enter',
            // lecturer
            'teach_isad',
            'teach_programming',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        Role::create(['name' => 'student'])->givePermissionTo([
            'practicum.enroll',
            'practicum.enter',
            'archive.view',
        ]);

        Role::create(['name' => 'assistant'])->givePermissionTo([
            'academic_year.add',
            'academic_year.edit',
            'academic_year.delete',
            'academic_year.view',
            'shift.add',
            'shift.edit',
            'shift.delete',
            'shift.view',
            'practicum.add',
            'practicum.delete',
            'practicum.view',
            'archive.add',
            'archive.delete',
            'archive.view',
            'students.view',
            'student_datas.view',
            'schedule.add',
            'schedule.edit',
            'schedule.delete',
            'manage_records',
            'assignment.add',
            'assignment.edit',
            'assignment.delete',
            'assignment.view',
            'submission.view',
            'scores.calculate',
        ]);

        Role::create(['name' => 'lecturer'])->givePermissionTo([
            'teach_isad',
            'teach_programming',
            ...$viewPermissions,
        ]);

        Role::create(['name' => 'programming_lecturer'])->givePermissionTo([
            'teach_programming',
            ...$viewPermissions,
        ]);

        Role::create(['name' => 'apsi_lecturer'])->givePermissionTo([
            'teach_isad',
            ...$viewPermissions,
        ]);

        Role::create(['name' => 'lab_tech']);
    }
}
