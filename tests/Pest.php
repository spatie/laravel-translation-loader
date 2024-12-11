<?php

use Spatie\TranslationLoader\LanguageLine;
use Tests\TestCase;

uses(TestCase::class)->in('Feature');

function flushIlluminateTranslatorCache(): void
{
    app('translator')->setLoaded([]);
}

function createLanguageLine(string $group, string $key, array $text): LanguageLine
{
    return LanguageLine::create(compact('group', 'key', 'text'));
}
