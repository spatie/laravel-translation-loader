<?php

namespace Spatie\DbLanguageLines\Test;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\DbLanguageLines\DbLanguageLinesServiceProvider;
use Spatie\DbLanguageLines\LanguageLine;

abstract class TestCase extends Orchestra
{
    /** @var \Spatie\DbLanguageLines\LanguageLine */
    protected $languageLine;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');

        $this->languageLine = $this->createTranslation('group', 'key', ['en' => 'english', 'nl' => 'nederlands']);
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
        $dbPath = __DIR__.'/temp/database.sqlite';

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

    protected function createTranslation(string $group, string $key, array $text): LanguageLine
    {
        return LanguageLine::create(compact('group', 'key', 'text'));
    }
}
