<?php

namespace Spatie\DbLanguageLines\Test;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\DbLanguageLines\DbLanguageLinesServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            DbLanguageLinesServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['path.lang'] = $this->getFixturesDirectory('lang');

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->createSqliteDatabase(),
            'prefix' => '',
        ]);


    }

    protected function createSqliteDatabase(): string
    {
        $dbPath = __DIR__."/temp/database.sqlite";

        if (file_exists($dbPath)) {
            unlink($dbPath);
        }

        touch($dbPath);

        return $dbPath;
    }

    public function getFixturesDirectory(string $path): string
    {
        return __DIR__."/fixtures/{$path}";
    }

    public function getTempDirectory(string $path): string
    {
        return __DIR__."/{$path}";
    }
}
