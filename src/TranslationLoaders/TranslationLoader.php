<?php

namespace Spatie\TranslationLoader\TranslationLoaders;

interface TranslationLoader
{
    /**
     * Returns all translations for the given locale and group.
     *
     * @param string $locale
     * @param string $group
     *
     * @return array
     */
    public function loadTranslations(string $locale, string $group): array;
}
