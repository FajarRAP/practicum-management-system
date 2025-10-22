<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PracticumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionnaireController;
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

    Route::middleware(['hasRole:lecturer,assistant'])->group(function () {
        Route::post('/archive', [ArchiveController::class, 'store'])->name('archive.store');
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
        Route::put('/users/{user}/assign-practicums', [UserController::class, 'updatePracticumAssignments'])->name('users.assign-practicums');
        Route::get('/role-management', [RolePermissionController::class, 'index'])->name('role-permission.index');
        Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'updatePermissions'])->name('role.permissions.update');
    });

    Route::get('practicum/{practicum}', [PracticumController::class, 'show'])->name('practicum.show');

    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');

    Route::get('/questionnaire', [QuestionnaireController::class, 'index'])->name('questionnaire.index');
    Route::get('/questionnaire/{questionnaire}', [QuestionnaireController::class, 'show'])->name('questionnaire.show');
    Route::post('/questionnaire', [QuestionnaireController::class, 'store'])->name('questionnaire.store');
    Route::put('/questionnaire/{questionnaire}', [QuestionnaireController::class, 'update'])->name('questionnaire.update');
    Route::delete('/questionnaire/{questionnaire}', [QuestionnaireController::class, 'destroy'])->name('questionnaire.destroy');

    Route::get('/questionnaire/answer/{questionnaire}', [AnswerController::class, 'index'])->name('answer.index');
    Route::get('/questionnaire/answer/{questionnaire}/fill', [AnswerController::class, 'create'])->name('answer.create');
    Route::post('/questionnaire/answer/{questionnaire}', [AnswerController::class, 'store'])->name('answer.store');

    Route::get('/questionnaire/manage-questions/{questionnaire}', [QuestionController::class, 'index'])->name('question.index');
    Route::post('/question/{questionnaire}', [QuestionController::class, 'store'])->name('question.store');
    Route::put('/question/{question}', [QuestionController::class, 'update'])->name('question.update');
    Route::delete('/question/{question}', [QuestionController::class, 'destroy'])->name('question.destroy');
});

require __DIR__ . '/auth.php';
