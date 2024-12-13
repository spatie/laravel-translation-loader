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
    expect(trans('file.key'))->toEqual('en value')
        ->and(trans('file.404.title'))->toEqual('page not found')
        ->and(trans('file.404.message'))->toEqual('This page does not exists');
});

it('can get translations for language files for the current locale', function () {
    app()->setLocale('nl');

    expect(trans('file.key'))->toEqual('nl value')
        ->and(trans('file.404.title'))->toEqual('pagina niet gevonden')
        ->and(trans('file.404.message'))->toEqual('Deze pagina bestaat niet');
});

it('it will prefer a db translation over a file translation by default', function () {
    createLanguageLine('*', 'file', 'key', ['en' => 'en value from db']);
    createLanguageLine('*', 'file', '404.title', ['en' => 'page not found from db']);
    createLanguageLine('validation', 'string', 'required', ['en' => 'The filed is required']);

    expect(trans('file.key'))->toEqual('en value from db')
        ->and(trans('file.404.title'))->toEqual('page not found from db')
        ->and(trans('file.404.message'))->toEqual('This page does not exists')
        ->and(trans('validation::string.required'))->toEqual('The filed is required');
});

it('will return array if the given translation is nested', function () {
    foreach (Arr::dot($this->nested) as $key => $text) {
        createLanguageLine('*', 'nested', $key, ['en' => $text]);
        createLanguageLine('namespace', 'nested', $key, ['en' => $text]);
    }

    expect(trans('nested.bool'))->toEqualCanonicalizing($this->nested['bool'], '$canonicalize = true')
        ->and(trans('namespace::nested.bool'))->toEqualCanonicalizing($this->nested['bool'], '$canonicalize = true');
});

it('will return the translation string if max nested level is reached', function () {
    foreach (Arr::dot($this->nested) as $key => $text) {
        createLanguageLine('*', 'nested', $key, ['en' => $text]);
        createLanguageLine('namespace', 'nested', $key, ['en' => $text]);
    }

    expect(trans('nested.bool.1'))->toEqual($this->nested['bool'][1])
        ->and(trans('namespace::nested.bool.1'))->toEqual($this->nested['bool'][1]);
})->only();

it('will return the dotted translation key if no translation found', function () {
    $notFoundKey = 'nested.bool.3';

    foreach (Arr::dot($this->nested) as $key => $text) {
        createLanguageLine('*', 'nested', $key, ['en' => $text]);
    }

    expect(trans($notFoundKey))->toEqual($notFoundKey);
});

it('will default to fallback if locale is missing', function () {
    app()->setLocale('de');
    createLanguageLine('*', 'missing_locale', 'key', ['en' => 'en value from db']);
    createLanguageLine('missing_namespace', 'missing_locale', 'key', ['en' => 'en value from db']);

    expect(trans('missing_locale.key'))->toEqual('en value from db')
        ->and(trans('missing_namespace::missing_locale.key'))->toEqual('en value from db');
});
