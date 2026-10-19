<?php

use Illuminate\Support\Facades\Schema;
use Spatie\TranslationLoader\TranslationLoaders\Db;
use Spatie\TranslationLoader\TranslationLoaders\TranslationLoader;
use Tests\Feature\DummyLoader;

it('will not use database translations if the provider is not configured', function () {
    config()->set('translation-loader.translation_loaders', []);

    expect(trans('group.key'))->toEqual('group.key');
});

it('will merge translation from all providers', function () {
    config()->set('translation-loader.translation_loaders', [
        Db::class,
        DummyLoader::class,
    ]);

    createLanguageLine('db', 'key', ['en' => 'db']);

    expect(trans('db.key'))->toEqual('db')
        ->and(trans('dummy.dummy'))->toEqual('this is dummy');
});

it('isolates namespaced database translations and preserves file translations', function () {
    app('translator')->addNamespace('customers', __DIR__ . '/../Fixtures/lang');
    app('translator')->addNamespace('support', __DIR__ . '/../Fixtures/lang');

    createLanguageLine('file', 'key', ['en' => 'global database value']);
    createLanguageLine('customers::file', 'key', ['en' => 'customer database value']);
    createLanguageLine('support::file', '404.title', ['en' => 'support database title']);

    expect(trans('customers::file.key'))->toBe('customer database value')
        ->and(trans('customers::file.404.title'))->toBe('page not found')
        ->and(trans('support::file.key'))->toBe('en value')
        ->and(trans('support::file.404.title'))->toBe('support database title')
        ->and(trans('file.key'))->toBe('global database value');
});

it('passes namespaces to custom translation loaders', function () {
    $loader = new class implements TranslationLoader {
        public function loadTranslations(string $locale, string $group, ?string $namespace = null): array
        {
            return ['key' => "{$namespace} {$locale} {$group}"];
        }
    };

    app()->instance(get_class($loader), $loader);
    config()->set('translation-loader.translation_loaders', [get_class($loader)]);

    expect(trans('customers::messages.key'))->toBe('customers en messages')
        ->and(trans('support::messages.key'))->toBe('support en messages');
});

it('uses the fallback locale for namespaced database translations', function () {
    createLanguageLine('customers::messages', 'hello', ['en' => 'Hello customer']);
    app()->setLocale('nl');

    expect(trans('customers::messages.hello'))->toBe('Hello customer');
});

it('can load namespaced files before the database has been migrated', function () {
    app('translator')->addNamespace('customers', __DIR__ . '/../Fixtures/lang');
    Schema::drop('language_lines');

    expect(trans('customers::file.key'))->toBe('en value');
});

it('can load database translations without a namespace argument', function () {
    expect(app(Db::class)->loadTranslations('en', 'group'))->toBe(['key' => 'english']);
});
