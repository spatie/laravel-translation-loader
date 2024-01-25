<?php

namespace Spatie\TranslationLoader\TranslationLoaders;

interface TranslationLoader
{
    /**
     * Returns all translations for the given locale and group.
     *
     * @param string $locale
     * @param string $group
     * @param $namespace
     *
     * @return array
     */
    public function loadTranslations(string $locale, string $group, $namespace = null): array;
}
