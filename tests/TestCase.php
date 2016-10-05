<?php

namespace Spatie\DbLanguageLines\Test;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\DbLanguageLines\DbLanguageLinesServiceProvider;

abstract class TestCase extends Orchestra
{
    /** @var \Spatie\DbLanguageLines\Test */
    protected $testHelper;

    public function setUp()
    {
        $this->testHelper = new TestHelper();

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
        $this->createDatabase();

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->testHelper->getTempDirectory().'/database.sqlite',
            'prefix' => '',
        ]);


    }

    protected function createDatabase()
    {
        $this->testHelper->initializeTempDirectory();

        file_put_contents($this->testHelper->getTempDirectory().'/database.sqlite', null);
    }
}
