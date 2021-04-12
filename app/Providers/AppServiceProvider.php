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

        $this->app->bind(\Mailjet\Client::class, function ($app) {
            return new \Mailjet\Client(
                config('services.email.mailjet.api_key'),
                config('services.email.mailjet.api_secret'),
                true,
                ['version' => 'v3.1'],
            );
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
