# Store your language lines in the database

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-db-language-lines.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-db-language-lines)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-db-language-lines/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-db-language-lines)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/5215e908-470a-4351-b39f-7149e8f85b6d.svg?style=flat-square)](https://insight.sensiolabs.com/projects/5215e908-470a-4351-b39f-7149e8f85b6d)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-db-language-lines.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-db-language-lines)
[![StyleCI](https://styleci.io/repos/70038687/shield?branch=master)](https://styleci.io/repos/70038687)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-db-language-lines.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-db-language-lines)

In a vanilla Laravel installation you can use [language files](https://laravel.com/docs/5.3/localization) to localize your app. This package will enable the translations to be stored in the database. You can still use all the features of [the `trans` function](https://laravel.com/docs/5.3/localization#retrieving-language-lines) you know and love.

```php
trans('messages.welcome', ['name' => 'dayle']);
``` 

You can even mix using language files and the database. If a translation is present in both a file and the database, the database version will be returned.

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment you are required to send us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

The best postcards will get published on the open source page on our website.

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-db-language-lines
```

In `config/app/php` you should replace Laravel's translation service provider

```php
Illuminate\Translation\TranslationServiceProvider::class,
``` 

by the one included in this package:

```php
Spatie\DbLanguageLines\TranslationServiceProvider::class,
```

You must run the migrations to create the `language_lines` table:

```bash
php artisan migrate
```

Optionally you could publish the config file using this command.

```bash
php artisan vendor:publish --provider="Spatie\DbLanguageLines\TranslationServiceProvide" --tag="config"
```

This is the contents of the published config file:

```php
return [

    /*
     * The model that handles the language lines. You can place any model here
     * that extends Spatie\DbLanguageLines\LanguageLine.
     */
    'model' => Spatie\DbLanguageLines\LanguageLine::class,
];
```

## Usage

You can create a translation in the database by creating and saving an instance of the `Spatie\DbLanguageLines\LanguageLine`-model:

```php
use Spatie\DbLanguageLines\LanguageLine;

LanguageLine::create([
   'group' => 'validation',
   'key' => 'required',
   'text' => ['en' => 'This is a required field', 'nl' => 'Dit is een verplicht veld'],
]);
```

That model uses the `HasTranslations` trait provided by [the `spatie/laravel-translatable` package](https://github.com/spatie/laravel-translatable).

You can fetch the translation with [Laravel's default `trans` function](https://laravel.com/docs/5.3/localization#retrieving-language-lines):

```php
trans('validation.required'); // returns 'This is a required field'

app()->setLocale('nl');

trans('validation.required'); // returns 'Dit is een verplicht veld'
```

You can still keep using the default language files as well. If a requested translation is present in both the database and the language files, the database version will be returned.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
