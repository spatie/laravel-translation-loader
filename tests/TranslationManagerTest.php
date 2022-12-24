<?php

use Spatie\TranslationLoader\Test\DummyLoader;
use Spatie\TranslationLoader\TranslationLoaders\Db;

it('will not use database translations if the provider is not configured', function () {
    $this->app['config']->set('translation-loader.translation_loaders', []);

    expect(trans('group.key'))->toEqual('group.key');
});

it('will merge translation from all providers', function () {
    $this->app['config']->set('translation-loader.translation_loaders', [
        Db::class,
        DummyLoader::class,
    ]);

    $this->createLanguageLine('db', 'key', ['en' => 'db']);

    expect(trans('db.key'))->toEqual('db')
        ->and(trans('dummy.dummy'))->toEqual('this is dummy');
});
