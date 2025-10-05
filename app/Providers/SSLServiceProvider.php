<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use Laravel\Socialite\SocialiteManager;

class SSLServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register custom HTTP client for Socialite
        $this->app->singleton('socialite.google.client', function ($app) {
            return new Client([
                'verify' => false,
                'timeout' => 30,
                'http_errors' => false,
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Override Socialite's HTTP client for Google OAuth
        if (app()->environment('local')) {
            $this->app->extend('Laravel\Socialite\Contracts\Factory', function ($socialite, $app) {
                $socialite->extend('google', function ($app) {
                    $config = $app['config']['services.google'];
                    $provider = new \Laravel\Socialite\Two\GoogleProvider(
                        $app['request'],
                        $config['client_id'],
                        $config['client_secret'],
                        $config['redirect']
                    );
                    
                    // Set custom HTTP client
                    $provider->setHttpClient($app['socialite.google.client']);
                    
                    return $provider;
                });
                
                return $socialite;
            });
        }
    }
}
