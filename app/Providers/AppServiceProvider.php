<?php

namespace App\Providers;

use App\Events\ReportSubmitted;
use App\Listeners\SendLoginSuccessEmail;
use App\Listeners\SendReportSubmissionEmail;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
        Event::listen(Registered::class, SendWelcomeEmail::class);
        Event::listen(Login::class, SendLoginSuccessEmail::class);
        Event::listen(ReportSubmitted::class, SendReportSubmissionEmail::class);
    }
}
