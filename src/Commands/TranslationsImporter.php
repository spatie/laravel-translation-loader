<?php

namespace Spatie\TranslationLoader\Commands;

interface TranslationsImporter
{
    public function createLanguageLine(string $group, string $key, array $text);
}