<?php

namespace Spatie\DbLanguageLines;

use Illuminate\Translation\LoaderInterface;
use Spatie\DbLanguageLines\TranslationLoaders\TranslationLoader;

class TranslationLoaderManager implements LoaderInterface
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
        return collect(config('laravel-db-language-lines.translationLoaders'))
            ->map(function(string $className) {
                return app($className);
            })
            ->map(function(TranslationLoader $translationLoader) use ($locale, $group, $namespace) {
                return $translationLoader->loadTranslations($locale, $group, $namespace);
            })
            ->reduce(function($allTranslations, $translations) {
                return array_merge($allTranslations, $translations);
            }, []);
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string $namespace
     * @param  string $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        // TODO: Implement addNamespace() method.
    }
}
