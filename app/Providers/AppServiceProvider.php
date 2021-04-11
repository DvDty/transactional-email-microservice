<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SendGrid;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SendGrid::class, function ($app) {
            return new SendGrid(config('services.email.sendgrid.api_key'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
