<?php

namespace Spatie\DbLanguageLines;

use Illuminate\Translation\FileLoader;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;

class TranslationServiceProvider extends IlluminateTranslationServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            $this->publishes([
               __DIR__.'/../config/laravel-db-language-lines.php' => config_path('laravel-db-language-lines.php'),
           ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__.'/../config/laravel-db-language-lines.php', 'laravel-db-language-lines');
    }

    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            $fileLoader = new FileLoader($app['files'], $app['path.lang']);

            return new TranslationLoaderManager($fileLoader);
        });
    }
}
