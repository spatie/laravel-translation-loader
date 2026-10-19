<?php

namespace Spatie\TranslationLoader\TranslationLoaders;

interface TranslationLoader
{
    /**
     * Returns all translations for the given locale, group, and namespace.
     *
     * @return array<string, mixed>
     */
    public function loadTranslations(string $locale, string $group, ?string $namespace = null): array;
}
