<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PracticumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StudentPracticumController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::fallback(fn() => redirect(route('dashboard')));

Route::get('/', fn() => redirect(route('login')));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('practicum/{practicum}', [PracticumController::class, 'show'])->name('practicum.show');
    Route::middleware(['hasRole:lecturer,assistant,apsi_lecturer,programming_lecturer'])->group(function () {
        Route::resource('academic-year', AcademicYearController::class)->except(['create', 'edit']);
        Route::resource('practicum', PracticumController::class)->except(['create', 'edit', 'show']);
        Route::resource('shift', ShiftController::class)->except(['create', 'edit']);

        Route::resource('practicum.assignment', AssignmentController::class)->shallow()->only(['store', 'update', 'destroy']);
        Route::resource('schedule', ScheduleController::class)->only(['store', 'update', 'destroy']);

        Route::get('practicum/{practicum}/assignment/{assignment}/submissions', [AssignmentSubmissionController::class, 'index'])
            ->name('assignment-submission.index');

        Route::get('/assignment', [AssignmentController::class, 'index'])
            ->name('assignment.index');
        Route::post('/assignment', [AssignmentController::class, 'store'])
            ->name('assignment.store');
        Route::put('/practicum/{practicum}/assignment/{assignment}', [AssignmentController::class, 'update'])
            ->name('assignment.update');
        Route::delete('/practicum/{practicum}/assignment/{assignment}', [AssignmentController::class, 'destroy'])
            ->name('assignment.destroy');

        Route::get('/practicum/{practicum}/schedule/{schedule}', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::post('/practicum/{practicum}/calculate-scores', [PracticumController::class, 'calculateScores'])->name('practicum.calculate-scores');
    });

    Route::middleware(['hasRole:student'])->group(function () {
        Route::get('/enrollment', [EnrollmentController::class, 'index'])->name('enrollment.index');
        Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store');
        Route::delete('/enrollment/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollment.destroy');

        Route::get('/my-practicum', [StudentPracticumController::class, 'index'])->name('my-practicum.index');

        Route::post('/assignment-submission/{assignment}', [AssignmentSubmissionController::class, 'store'])->name('assignment-submission.store');
    });

    Route::middleware(['hasRole:lab_tech'])->group(function () {
        Route::patch('/schedule/{schedule}/approve', [ScheduleController::class, 'approve'])->name('schedule.approve');
        Route::patch('/schedule/{schedule}/reject', [ScheduleController::class, 'reject'])->name('schedule.reject');
        Route::resource('users', UserController::class)->only(['index', 'update']);
        Route::get('/role-management', [RolePermissionController::class, 'index'])->name('role-permission.index');
        Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'updatePermissions'])->name('role.permissions.update');
    });

    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');
    Route::post('/archive', [ArchiveController::class, 'store'])->name('archive.store');
});

require __DIR__ . '/auth.php';
