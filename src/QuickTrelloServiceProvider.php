<?php

namespace QuickTrello;

use Illuminate\Support\ServiceProvider;
use QuickTrello\Services\TrelloService;

class QuickTrelloServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/Config/quicktrello.php', 'quicktrello'
        );

        // Register the service
        $this->app->singleton('quicktrello', function ($app) {
            return new TrelloService(
                config('quicktrello.api_key'),
                config('quicktrello.token'),
                config('quicktrello.base_url', 'https://api.trello.com/1')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/Config/quicktrello.php' => config_path('quicktrello.php'),
        ], 'quicktrello-config');

        // Load routes
        if (config('quicktrello.register_routes', true)) {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        }
    }
}