<?php

namespace Spatie\TranslationLoader;

use Illuminate\Translation\FileLoader;
use Spatie\TranslationLoader\TranslationLoaders\TranslationLoader;

class TranslationLoaderManager extends FileLoader
{
    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        $loaderTranslations = $this->getTranslationsForTranslationLoaders($locale, $group, $namespace);

        return $loaderTranslations + $fileTranslations;
    }

    protected function getTranslationsForTranslationLoaders(
        string $locale,
        string $group,
        string $namespace = null
    ): array {
        return collect(config('laravel-translation-loader.translation_loaders'))
            ->map(function (string $className) {
                return app($className);
            })
            ->mapWithKeys(function (TranslationLoader $translationLoader) use ($locale, $group, $namespace) {
                return $translationLoader->loadTranslations($locale, $group, $namespace);
            })
            ->toArray();
    }
}
