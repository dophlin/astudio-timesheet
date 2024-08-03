<?php

namespace App\Providers;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\TimesheetRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TimesheetRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(TimesheetRepositoryInterface::class, TimesheetRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
