<?php

use Spatie\TranslationLoader\Test\TranslationManagers\DummyManager;

trait SetupDummyManagerTest {
    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('translation-loader.translation_manager', DummyManager::class);
    }
}

uses(SetupDummyManagerTest::class);

it('allow to change translation manager', function () {
    $this->assertInstanceOf(DummyManager::class, $this->app['translation.loader']);
});

it('can translate using dummy manager using file', function () {
    $this->assertEquals('en value', trans('file.key'));
});

it('can translate using dummy manager using db', function () {
    $this->createLanguageLine('file', 'key', ['en' => 'en value from db']);
    $this->assertEquals('en value from db', trans('file.key'));
});

it('can translate using dummy manager using file with incomplete db', function () {
    $this->createLanguageLine('file', 'key', ['nl' => 'nl value from db']);
    $this->assertEquals('en value', trans('file.key'));
});

it('can translate using dummy manager using empty translation in db', function () {
    $this->createLanguageLine('file', 'key', ['en' => '']);

    // Some versions of Laravel changed the behaviour of what an empty "" translation value returns: the key name or an empty value
    // @see https://github.com/laravel/framework/issues/34218
    $this->assertTrue(in_array(trans('file.key'), ['', 'file.key']));
});
