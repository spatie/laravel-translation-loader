<?php

namespace Spatie\TranslationLoader;

use Illuminate\Translation\FileLoader;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;

class TranslationServiceProvider extends IlluminateTranslationServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__.'/../config/translation-loader.php', 'translation-loader');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole() && ! str_contains($this->app->version(), 'Lumen')) {
            $this->publishes([
                __DIR__.'/../config/translation-loader.php' => config_path('translation-loader.php'),
            ], 'config');

            if (! class_exists('CreateLanguageLinesTable')) {
                $timestamp = date('Y_m_d_His', time());

                $this->publishes([
                    __DIR__.'/../database/migrations/create_language_lines_table.php.stub' => database_path('migrations/'.$timestamp.'_create_language_lines_table.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register the translation line loader. This method registers a
     * `TranslationLoaderManager` instead of a simple `FileLoader` as the
     * applications `translation.loader` instance.
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            $class = config('translation-loader.translation_manager');

            return new $class($app['files'], $app['path.lang']);
        });
    }
}
