# Changelog

All notable changes to `laravel-translation-loader` will be documented in this file

## 2.7.0 - 2020-12-04

- add support for php 8.0
- drop support for laravel 5.8

## 2.6.3 - 2020-10-02

- bugfix: support translations from json files (#114)

## 2.6.2 - 2020-09-09

- Support Laravel 8

## 2.6.1 - 2020-04-09

- improve migration

## 2.6.0 - 2020-03-03

- drop support for PHP 7.1

## 2.5.0 - 2020-03-03

- add support for Laravel 7

## 2.4.0 - 2019-09-04

- make compatible with Laravel 6

## 2.3.0 - 2019-02-27

- drop support for Laravel 5.7 and below
- drop support for PHP 7.1 and below

## 2.2.4 - 2019-02-27

- add support for Laravel 5.8

## 2.2.3 - 2019-02-01

- use Arr:: and Str:: functions

## 2.2.2 - 2018-10-25

- make `flushGroupCache` public

## 2.2.1 - 2018-10-25

- drop support for Laravel 5.5 and 5.6

## 2.2.0 - 2018-08-30

- add support for Lumen

## 2.1.6 - 2018-08-28

- add support for Laravel 5.7

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
