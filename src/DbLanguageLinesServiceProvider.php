<?php

namespace Spatie\DbLanguageLines;

use Illuminate\Support\ServiceProvider;

class DbLanguageLinesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            /*
           $this->publishes([
               __DIR__.'/../config/skeleton.php' => config_path('skeleton.php'),
           ], 'config');
            */
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //$this->mergeConfigFrom(__DIR__.'/../config/config.php', 'skeleton');
    }
}
