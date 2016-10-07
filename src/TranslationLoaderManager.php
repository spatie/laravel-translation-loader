<?php

namespace Spatie\TranslationLoader;

use Illuminate\Translation\FileLoader;
use Spatie\TranslationLoader\TranslationLoaders\TranslationLoader;

class TranslationLoaderManager extends FileLoader
{
    protected $fileLoader;

    public function __construct(FileLoader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

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
        $fileTranslations = $this->fileLoader->load($locale, $group, $namespace);

        if (! is_null($namespace) && $namespace !== '*') {
            return $fileTranslations;
        }

        $loaderTranslations = $this->getTranslationsForTranslationLoaders($locale, $group, $namespace);

        return array_merge($fileTranslations, $loaderTranslations);
    }

    protected function getTranslationsForTranslationLoaders(string $locale, string $group, string $namespace = null): array
    {
        $loaderTranslations = collect(config('laravel-translation-loader.translationLoaders'))
            ->map(function (string $className) {
                return app($className);
            })
            ->map(function (TranslationLoader $translationLoader) use ($locale, $group, $namespace) {
                return $translationLoader->loadTranslations($locale, $group, $namespace);
            })
            ->reduce(function ($allTranslations, $translations) {
                return array_merge($allTranslations, $translations);
            }, []);

        return $loaderTranslations;
    }
}
