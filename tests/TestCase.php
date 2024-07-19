<?php

declare(strict_types=1);

namespace Spatie\TranslationLoader\Test;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\TranslationLoader\LanguageLine;
use Spatie\TranslationLoader\TranslationServiceProvider;

abstract class TestCase extends Orchestra
{
    /** @var LanguageLine */
    protected $languageLine;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');

        $LanguageLinesTable = require __DIR__ . '/../database/migrations/create_language_lines_table.php.stub';

        $LanguageLinesTable->up();

        $this->languageLine = createLanguageLine('group', 'key', ['en' => 'english', 'nl' => 'nederlands']);
    }

    /**
     * @param  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['path.lang'] = $this->getFixturesDirectory('lang');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * @param  string  $path
     *
     * @return string
     */
    protected function getFixturesDirectory(string $path): string
    {
        return __DIR__ . "/fixtures/{$path}";
    }

    /**
     * @param  Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TranslationServiceProvider::class,
        ];
    }
}
