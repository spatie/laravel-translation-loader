<?php

use Illuminate\Support\Arr;

beforeEach(function () {
    $this->nested = [
        'bool' => [
            1 => 'Yes',
            0 => 'No',
        ],
    ];
});

it('can get translations for language files', function () {
    $this->assertEquals('en value', trans('file.key'));
    $this->assertEquals('page not found', trans('file.404.title'));
    $this->assertEquals('This page does not exists', trans('file.404.message'));
});

it('can get translations for language files for the current locale', function () {
    app()->setLocale('nl');

    $this->assertEquals('nl value', trans('file.key'));
    $this->assertEquals('pagina niet gevonden', trans('file.404.title'));
    $this->assertEquals('Deze pagina bestaat niet', trans('file.404.message'));
});

it('by default it will prefer a db translation over a file translation', function () {
    $this->createLanguageLine('file', 'key', ['en' => 'en value from db']);
    $this->createLanguageLine('file', '404.title', ['en' => 'page not found from db']);

    $this->assertEquals('en value from db', trans('file.key'));
    $this->assertEquals('page not found from db', trans('file.404.title'));
    $this->assertEquals('This page does not exists', trans('file.404.message'));
});

it('will return array if the given translation is nested', function () {
    foreach (Arr::dot($this->nested) as $key => $text) {
        $this->createLanguageLine('nested', $key, ['en' => $text]);
    }

    $this->assertEqualsCanonicalizing($this->nested['bool'], trans('nested.bool'), '$canonicalize = true', $delta = 0.0, $maxDepth = 10, $canonicalize = true);
});

it('will return the translation string if max nested level is reached', function () {
    foreach (Arr::dot($this->nested) as $key => $text) {
        $this->createLanguageLine('nested', $key, ['en' => $text]);
    }

    $this->assertEquals($this->nested['bool'][1], trans('nested.bool.1'));
});

it('will return the dotted translation key if no translation found', function () {
    $notFoundKey = 'nested.bool.3';

    foreach (Arr::dot($this->nested) as $key => $text) {
        $this->createLanguageLine('nested', $key, ['en' => $text]);
    }

    $this->assertEquals($notFoundKey, trans($notFoundKey));
});

it('will default to fallback if locale is missing', function () {
    app()->setLocale('de');
    $this->createLanguageLine('missing_locale', 'key', ['en' => 'en value from db']);

    $this->assertEquals('en value from db', trans('missing_locale.key'));
});
