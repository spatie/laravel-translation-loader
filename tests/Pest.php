<?php

use Spatie\TranslationLoader\LanguageLine;
use Tests\TestCase;

uses(TestCase::class)->in('Feature');

function flushIlluminateTranslatorCache(): void
{
    app('translator')->setLoaded([]);
}

function createLanguageLine(string $namespace, string $group, string $key, array $text): LanguageLine
{
    return LanguageLine::create(compact('namespace', 'group', 'key', 'text'));
}
