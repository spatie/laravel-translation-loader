<?php

use Tests\Feature\TranslationManagers\DummyManager;

beforeEach(function () {
    config()->set('translation-loader.translation_manager', DummyManager::class);
});

it('allow to change translation manager', function () {
    expect($this->app['translation.loader'])->toBeInstanceOf(DummyManager::class);
});

it('can translate using dummy manager using file', function () {
    expect(trans('file.key'))->toEqual('en value');
});

it('can translate using dummy manager using db', function () {
    createLanguageLine('*', 'file', 'key', ['en' => 'en value from db']);
    expect(trans('file.key'))->toEqual('en value from db');
});

it('can translate using dummy manager using file with incomplete db', function () {
    createLanguageLine('*', 'file', 'key', ['nl' => 'nl value from db']);
    expect(trans('file.key'))->toEqual('en value');
});

it('can translate using dummy manager using empty translation in db', function () {
    createLanguageLine('*', 'file', 'key', ['en' => '']);

    // Some versions of Laravel changed the behaviour of what an empty "" translation value returns: the key name or an empty value
    // @see https://github.com/laravel/framework/issues/34218
    expect(in_array(trans('file.key'), ['', 'file.key']))->toBeTrue();
});
