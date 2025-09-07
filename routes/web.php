<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PracticumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StudentPracticumController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect(route('login')));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/dashboard/schedule', [ScheduleController::class, 'index'])
//     ->middleware(['auth', 'verified'])->name('schedule.index');
// Route::get('/dashboard/schedule/{schedule}', [ScheduleController::class, 'show'])
//     ->middleware(['auth', 'verified'])->name('schedule.show')->whereNumber('schedule');
// Route::post('/dashboard/schedule', [ScheduleController::class, 'store'])
//     ->middleware(['auth', 'verified'])->name('schedule.store');

Route::get('/dashboard/announcement', [AnnouncementController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('announcement.index');
Route::post('/dashboard/announcement', [AnnouncementController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('announcement.store');
Route::patch('/dashboard/announcement/{announcement}/confirm', [AnnouncementController::class, 'confirmAnnouncement'])
    ->middleware(['auth', 'verified'])->name('announcement.confirm');

Route::get('/dashboard/assignment', [AssignmentController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('assignment.index');
Route::post('/dashboard/assignment', [AssignmentController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('assignment.store');

Route::get('/dashboard/assessment', [AssessmentController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('assessment.index');
Route::get('/dashboard/assessment/{announcement}', [AssessmentController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('assessment.show')->whereNumber('announcement');
Route::get('/dashboard/assessment/final-score', [AssessmentController::class, 'finalScore'])
    ->middleware(['auth', 'verified'])->name('assessment.final-score');
Route::post('/dashboard/assessment/{announcement}', [AssessmentController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('assessment.store')->whereNumber('announcement');

Route::get('/dashboard/enrollment', [EnrollmentController::class, 'index'])
    ->middleware(['auth', 'verified', 'hasRole:student'])->name('enrollment.index');
Route::post('/dashboard/enrollment', [EnrollmentController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('enrollment.store');

Route::get('/dashboard/assignment/{assignment}/submission', [AssignmentSubmissionController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('assignment-submission.index')->whereNumber('assignment');
Route::post('/dashboard/assignment/{assignment}/submission', [AssignmentSubmissionController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('assignment-submission.store')->whereNumber('assignment');

Route::get('/dashboard/archive', [ArchiveController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('archive.index');
Route::post('/dashboard/archive', [ArchiveController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('archive.store');

Route::get('/dashboard/academic-year', [AcademicYearController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('academic-year.index');
Route::post('/dashboard/academic-year', [AcademicYearController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('academic-year.store');
Route::delete('/dashboard/academic-year/{academicYear}', [AcademicYearController::class, 'destroy'])
    ->middleware(['auth', 'verified'])->name('academic-year.destroy');
Route::put('/dashboard/academic-year/{academicYear}', [AcademicYearController::class, 'update'])
    ->middleware(['auth', 'verified'])->name('academic-year.update');

Route::get('/dashboard/practicum', [PracticumController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('practicum.index');
Route::get('/dashboard/practicum/{practicum}', [PracticumController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('practicum.show');
Route::post('/dashboard/practicum', [PracticumController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('practicum.store');
Route::delete('/dashboard/practicum/{practicum}', [PracticumController::class, 'destroy'])
    ->middleware(['auth', 'verified'])->name('practicum.destroy');
Route::put('/dashboard/practicum/{practicum}', [PracticumController::class, 'update'])
    ->middleware(['auth', 'verified'])->name('practicum.update');
Route::get('/dashboard/practicum/{practicum}/schedule/{schedule}/attendance', [AttendanceController::class, 'index'])
    ->middleware(['auth', 'verified', 'hasRole:assistant'])->name('attendance.index');
Route::post('/dashboard/practicum/{practicum}/schedule/{schedule}/attendance', [AttendanceController::class, 'store'])
    ->middleware(['auth', 'verified', 'hasRole:assistant'])->name('attendance.store');

Route::get('/dashboard/shift', [ShiftController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('shift.index');
Route::post('/dashboard/shift', [ShiftController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('shift.store');
Route::put('/dashboard/shift/{shift}', [ShiftController::class, 'update'])
    ->middleware(['auth', 'verified'])->name('shift.update');
Route::delete('/dashboard/shift/{shift}', [ShiftController::class, 'destroy'])
    ->middleware(['auth', 'verified'])->name('shift.destroy');

Route::post('/dashboard/schedule', [ScheduleController::class, 'store'])
    ->middleware(['auth', 'verified', 'hasRole:assistant'])->name('schedule.store');
Route::put('/dashboard/schedule/{schedule}', [ScheduleController::class, 'update'])
    ->middleware(['auth', 'verified', 'hasRole:assistant'])->name('schedule.update');
Route::delete('/dashboard/schedule/{schedule}', [ScheduleController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'hasRole:assistant'])->name('schedule.destroy');

Route::get('/dashboard/student-practicum', [StudentPracticumController::class, 'index'])
    ->middleware(['auth', 'verified', 'hasRole:student'])->name('my-practicum.index');

require __DIR__ . '/auth.php';
