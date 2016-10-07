<?php

namespace Spatie\TranslationLoader\TranslationLoaders;

interface TranslationLoader
{
    public function loadTranslations(string $locale, string $group, string $namespace = null): array;
}
