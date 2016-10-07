<?php

namespace Spatie\DbLanguageLines;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;
use Spatie\DbLanguageLines\TranslationLoaders\PhpFile;

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

        $this->app->when(PhpFile::class)
            ->needs(Filesystem::class)
            ->give($this->app['files']);

        $this->app->when(PhpFile::class)
            ->needs('$path')
            ->give( $this->app['path.lang']);


        $this->mergeConfigFrom(__DIR__.'/../config/laravel-db-language-lines.php', 'laravel-db-language-lines');
    }

    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoaderManager($app['files'], $app['path.lang']);
        });
    }
}
