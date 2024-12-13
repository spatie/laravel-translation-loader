<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\TranslationLoader\LanguageLine;
use Spatie\TranslationLoader\TranslationServiceProvider;

abstract class TestCase extends Orchestra
{
    /** @var LanguageLine */
    protected LanguageLine $languageLine;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');

        (require __DIR__ . '/../database/migrations/create_language_lines_table.php.stub')->up();
        (require __DIR__ . '/../database/migrations/alter_language_lines_table_add_column_namespace.php.stub')->up();

        $this->languageLine = createLanguageLine('*', 'group', 'key', ['en' => 'english', 'nl' => 'nederlands']);
    }

    /**
     * @param  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['path.lang'] = __DIR__ . '/Fixtures/lang';

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * @param  Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            TranslationServiceProvider::class,
        ];
    }
}
