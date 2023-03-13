<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google\Client;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function() {
            $client = app(Client::class);
        
            $client->setClientId(Config::get('services.google-drive.id'));
            $client->setClientSecret(Config::get('services.google-drive.secret'));
            $client->setRedirectUri(Config::get('services.google-drive.redirect_url'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
