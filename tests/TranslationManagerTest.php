<?php

use Spatie\TranslationLoader\Test\DummyLoader;
use Spatie\TranslationLoader\TranslationLoaders\Db;

it('will not use database translations if the provider is not configured', function () {
    $this->app['config']->set('translation-loader.translation_loaders', []);

    $this->assertEquals('group.key', trans('group.key'));
});

it('will merge translation from all providers', function () {
    $this->app['config']->set('translation-loader.translation_loaders', [
        Db::class,
        DummyLoader::class,
    ]);

    $this->createLanguageLine('db', 'key', ['en' => 'db']);

    $this->assertEquals('db', trans('db.key'));
    $this->assertEquals('this is dummy', trans('dummy.dummy'));
});
