# Changelog

All notable changes to `laravel-translation-loader` will be documented in this file

## 2.1.5 - 2018-07-30

- a non-existing translation will now return `null` instead of an empty string

## 2.1.4 - 2018-03-06

- avoid throwing an exception when retrieving a non existing translation

## 2.1.3 - 2018-03-04

- fix bug when used with other traits

## 2.1.2 - 2018-02-07

- add support for Laravel 5.6

## 2.1.1 - 2017-12-01

- fix for not using fallback locale

## 2.1.0 - 2017-10-19

- add `translation_manager` config key
- fix for missing keys (issue #49)

## 2.0.0 - 2017-08-31

- add support for Laravel 5.5, dropped support for Laravel 5.4
- rename config file from `laravel-translation-loader` to `translation-loader`

## 1.2.1 - 2017-08-07

- add support for numeric keys
- drop support for Laravel 5.3

## 1.2.0 - 2017-07-07

- add support for nested translations

## 1.1.2 - 2017-06-26

- fix bug that prevented translations from vendor files being loaded

## 1.1.1 - 2017-05-15

- make `setTranslation` chainable

## 1.1.0 - 2016-10-23

- add compatibility with Laravel 5.4

## 1.0.1 - 2016-10-10

- fix exception when using `setTranslation` on a fresh instance of `LanguageLine`

## 1.0.0 - 2016-10-07

- initial release
