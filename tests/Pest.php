<?php

declare(strict_types=1);

use Spatie\TranslationLoader\LanguageLine;
use Tests\TestCase;

uses(TestCase::class)->in('Feature');

/**
 * @return void
 */
function flushIlluminateTranslatorCache(): void
{
    app('translator')->setLoaded([]);
}

/**
 * @param  string  $group
 * @param  string  $key
 * @param  array  $text
 *
 * @return LanguageLine
 */
function createLanguageLine(string $group, string $key, array $text): LanguageLine
{
    return LanguageLine::create(compact('group', 'key', 'text'));
}
