<?php

namespace App\Providers;

use App\Models\AcademicYear;
use App\Models\Archive;
use App\Models\Practicum;
use App\Models\Schedule;
use App\Models\User;
use App\Policies\AcademicYearPolicy;
use App\Policies\ArchivePolicy;
use App\Policies\PracticumPolicy;
use App\Policies\SchedulePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View as FacadeView;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FacadeView::composer('layouts.app', function (View $view) {
            $activeAcademicYear = AcademicYear::where('status', 'ACTIVE')->first();
            $view->with('activeAcademicYear', $activeAcademicYear);
        });

        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('lab_tech')) {
                return true;
            }
        });

        Gate::policy(Practicum::class, PracticumPolicy::class);
        Gate::policy(Schedule::class, SchedulePolicy::class);
        Gate::policy(AcademicYear::class, AcademicYearPolicy::class);
        Gate::policy(Archive::class, ArchivePolicy::class);
    }
}
