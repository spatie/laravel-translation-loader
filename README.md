# Store your language lines in the database

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-translation-loader.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-translation-loader)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-translation-loader/run-tests?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-translation-loader.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-translation-loader)

In a vanilla Laravel or Lumen installation you can use [language files](https://laravel.com/docs/5.6/localization) to localize your app. This package will enable the translations to be stored in the database. You can still use all the features of [the `trans` function](https://laravel.com/docs/5.6/localization#retrieving-translation-strings) you know and love.

```php
trans('messages.welcome', ['name' => 'dayle']);
```

You can even mix using language files and the database. If a translation is present in both a file and the database, the database version will be returned.

Want to use a different source for your translations? No problem! The package is [easily extendable](https://github.com/spatie/laravel-translation-loader#creating-your-own-translation-providers).

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-translation-loader.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-translation-loader)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-translation-loader
```

In `config/app.php` (Laravel) or `bootstrap/app.php` (Lumen) you should replace Laravel's translation service provider

```php
Illuminate\Translation\TranslationServiceProvider::class,
```

by the one included in this package:

```php
Spatie\TranslationLoader\TranslationServiceProvider::class,
```

You must publish and run the migrations to create the `language_lines` table otherwise only file translations will be loaded:

```bash
php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="migrations"
php artisan migrate
```

Optionally you could publish the config file using this command.

```bash
php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [

    /*
     * Language lines will be fetched by these loaders. You can put any class here that implements
     * the Spatie\TranslationLoader\TranslationLoaders\TranslationLoader-interface.
     */
    'translation_loaders' => [
        Spatie\TranslationLoader\TranslationLoaders\Db::class,
    ],

    /*
     * This is the model used by the Db Translation loader. You can put any model here
     * that extends Spatie\TranslationLoader\LanguageLine.
     */
    'model' => Spatie\TranslationLoader\LanguageLine::class,

    /*
     * This is the translation manager which overrides the default Laravel `translation.loader`
     */
    'translation_manager' => Spatie\TranslationLoader\TranslationLoaderManager::class,

];
```

> **Note:** publishing assets doesn't work out of the box in Lumen. Instead you have to copy the files from the repo.

## Usage

You can create a translation in the database by creating and saving an instance of the `Spatie\TranslationLoader\LanguageLine`-model:

```php
use Spatie\TranslationLoader\LanguageLine;

LanguageLine::create([
   'group' => 'validation',
   'key' => 'required',
   'text' => ['en' => 'This is a required field', 'nl' => 'Dit is een verplicht veld'],
]);
```

You can fetch the translation with [Laravel's default `trans` function](https://laravel.com/docs/5.3/localization#retrieving-language-lines):

```php
trans('validation.required'); // returns 'This is a required field'

app()->setLocale('nl');

trans('validation.required'); // returns 'Dit is een verplicht veld'
```

You can still keep using the default language files as well. If a requested translation is present in both the database and the language files, the database version will be returned.

If you need to store/override json translation lines, just create a normal LanguageLine with `group => '*'`.

## Creating your own translation providers

This package ships with a translation provider than can fetch translations from the database. If you're storing your translations in a yaml-file, a csv-file, or ... you can easily extend this package by creating your own translation provider.

A translation provider can be any class that implements the `Spatie\TranslationLoader\TranslationLoaders\TranslationLoader`-interface. It contains only one method:

```php
namespace Spatie\TranslationLoader\TranslationLoaders;

interface TranslationLoader
{
    /*
     * Returns all translations for the given locale and group.
     */
    public function loadTranslations(string $locale, string $group): array;
}
```

Translation providers can be registered in the `translation_loaders` key of the config file.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
