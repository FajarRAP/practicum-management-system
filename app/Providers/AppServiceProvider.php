<?php

namespace App\Providers;

use App\Models\AcademicYear;
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
    }
}
