<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;


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
        //
        // Passport::routes();
        // Passport::tokensExpireIn(now()->addHours(8));
        // Passport::refreshTokensExpireIn(now()->addDays(10));
    }

}
