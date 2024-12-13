<?php

use Spatie\TranslationLoader\Exceptions\InvalidConfiguration;
use Spatie\TranslationLoader\LanguageLine;

it('can get a translation for the current app locale', function () {
    expect(trans('group.key'))->toEqual('english');
});

it('can get a correct translation after the locale has been changed', function () {
    app()->setLocale('nl');

    expect(trans('group.key'))->toEqual('nederlands');
});

it('can return the group and the key when getting a non existing translation', function () {
    app()->setLocale('nl');

    expect(trans('group.unknown'))->toEqual('group.unknown');
});

it('supports placeholders', function () {
    createLanguageLine('*', 'group', 'placeholder', ['en' => 'text with :placeholder']);

    expect(trans('group.placeholder', ['placeholder' => 'filled in placeholder']))->toEqual('text with filled in placeholder');
});

it('will cache all translations', function () {
    trans('group.key');

    $queryCount = count(DB::getQueryLog());
    flushIlluminateTranslatorCache();

    trans('group.key');

    expect(count(DB::getQueryLog()))->toEqual($queryCount);
});

it('flushes the cache when a translation has been created', function () {
    expect(trans('group.new'))->toEqual('group.new');

    createLanguageLine('*', 'group', 'new', ['en' => 'created']);
    flushIlluminateTranslatorCache();

    expect(trans('group.new'))->toEqual('created');
});

it('flushes the cache when a translation has been updated', function () {
    trans('group.key');

    $this->languageLine->setTranslation('en', 'updated');
    $this->languageLine->save();

    flushIlluminateTranslatorCache();

    expect(trans('group.key'))->toEqual('updated');
});

it('flushes the cache when a translation has been deleted', function () {
    expect(trans('group.key'))->toEqual('english');

    $this->languageLine->delete();
    flushIlluminateTranslatorCache();

    expect(trans('group.key'))->toEqual('group.key');
});

it('can work with a custom model', function () {
    $alternativeModel = new class extends LanguageLine {
        public static function getTranslationsForGroup(string $locale, string $group, string|null $namespace = null): array
        {
            return ['key' => 'alternative class'];
        }
    };
    config()->set('translation-loader.model', get_class($alternativeModel));

    expect(trans('group.key'))->toEqual('alternative class');
});
it('will throw an exception if the configured model does not extend the default one', function () {
    $invalidModel = new class {};

    config()->set('translation-loader.model', get_class($invalidModel));

    $this->expectException(InvalidConfiguration::class);

    expect(trans('group.key'))->toEqual('alternative class');
});
