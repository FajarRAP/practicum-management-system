<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect(route('login')));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard/schedule', [ScheduleController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('schedule.index');
Route::get('/dashboard/schedule/{schedule}', [ScheduleController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('schedule.show');
Route::post('/dashboard/schedule', [ScheduleController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('schedule.store');

Route::get('/dashboard/announcement', [AnnouncementController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('announcement.index');
Route::post('/dashboard/announcement', [AnnouncementController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('announcement.store');

Route::get('/dashboard/assignment', [AssignmentController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('assignment.index');
Route::post('/dashboard/assignment', [AssignmentController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('assignment.store');

Route::get('/dashboard/attendance', [AttendanceController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('attendance.index');
Route::get('/dashboard/attendance/{announcement}', [AttendanceController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('attendance.show');
Route::post('/dashboard/attendance/{announcement}', [AttendanceController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('attendance.store');

Route::get('/dashboard/assessment', [AssessmentController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('assessment.index');
Route::get('/dashboard/assessment/{announcement}', [AssessmentController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('assessment.show');
Route::post('/dashboard/assessment/{announcement}', [AssessmentController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('assessment.store');

Route::get('/dashboard/enrollment', [EnrollmentController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('enrollment.index');
Route::post('/dashboard/enrollment', [EnrollmentController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('enrollment.store');

Route::get('/dashboard/assignment/{assignment}/submission', [AssignmentSubmissionController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('assignment-submission.index');
Route::post('/dashboard/assignment/{assignment}/submission', [AssignmentSubmissionController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('assignment-submission.store');

require __DIR__ . '/auth.php';
