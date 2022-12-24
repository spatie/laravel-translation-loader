<?php

use Illuminate\Support\Facades\DB;
use Spatie\TranslationLoader\Exceptions\InvalidConfiguration;
use Spatie\TranslationLoader\LanguageLine;

function flushIlluminateTranslatorCache(): void
{
    test()->app['translator']->setLoaded([]);
}

it('can get a translation for the current app locale', function () {
    $this->assertEquals('english', trans('group.key'));
});

it('can get a correct translation after the locale has been changed', function () {
    app()->setLocale('nl');

    $this->assertEquals('nederlands', trans('group.key'));
});

it('can return the group and the key when getting a non existing translation', function () {
    app()->setLocale('nl');

    $this->assertEquals('group.unknown', trans('group.unknown'));
});

it('supports placeholders', function () {
    $this->createLanguageLine('group', 'placeholder', ['en' => 'text with :placeholder']);

    $this->assertEquals(
        'text with filled in placeholder',
        trans('group.placeholder', ['placeholder' => 'filled in placeholder'])
    );
});

it('will cache all translations', function () {
    trans('group.key');

    $queryCount = count(DB::getQueryLog());
    flushIlluminateTranslatorCache();

    trans('group.key');

    $this->assertEquals($queryCount, count(DB::getQueryLog()));
});

it('flushes the cache when a translation has been created', function () {
    $this->assertEquals('group.new', trans('group.new'));

    $this->createLanguageLine('group', 'new', ['en' => 'created']);
    flushIlluminateTranslatorCache();

    $this->assertEquals('created', trans('group.new'));
});

it('flushes the cache when a translation has been updated', function () {
    trans('group.key');

    $this->languageLine->setTranslation('en', 'updated');
    $this->languageLine->save();

    flushIlluminateTranslatorCache();

    $this->assertEquals('updated', trans('group.key'));
});

it('flushes the cache when a translation has been deleted', function () {
    $this->assertEquals('english', trans('group.key'));

    $this->languageLine->delete();
    flushIlluminateTranslatorCache();

    $this->assertEquals('group.key', trans('group.key'));
});

it('can work with a custom model', function () {
    $alternativeModel = new class extends LanguageLine {
        protected $table = 'language_lines';
        public static function getTranslationsForGroup(string $locale, string $group): array
        {
        return ['key' => 'alternative class'];
        }
    };

    $this->app['config']->set('translation-loader.model', get_class($alternativeModel));

    $this->assertEquals('alternative class', trans('group.key'));
});

it('will throw an exception if the configured model does not extend the default one', function () {
    $invalidModel = new class {
    };

    $this->app['config']->set('translation-loader.model', get_class($invalidModel));

    $this->expectException(InvalidConfiguration::class);

    $this->assertEquals('alternative class', trans('group.key'));
});
