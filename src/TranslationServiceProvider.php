<?php

namespace Spatie\TranslationLoader;

use Illuminate\Translation\FileLoader;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;
use Spatie\TranslationLoader\TranslationLoaders\PhpFile;

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
               __DIR__.'/../config/laravel-translation-loader.php' => config_path('laravel-translation-loader.php'),
           ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__.'/../config/laravel-translation-loader.php', 'laravel-translation-loader');
    }

    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {

            $fileLoader = new FileLoader($app['files'], $app['path.lang']);

            return new TranslationLoaderManager($fileLoader);
        });
    }
}
