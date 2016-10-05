<?php

namespace App\Services\Locale;

use Cache;
use Illuminate\Translation\FileLoader;
use Schema;

class TranslationLoader extends FileLoader
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
        if (!is_null($namespace) && $namespace !== '*') {
            return $this->loadNamespaced($locale, $group, $namespace);
        }

        if (!$this->languageLinesAreAvailable()) {
            return [];
        }

        return Cache::rememberForever(
            "locale.fragments.{$locale}.{$group}",
            function () use ($group, $locale) {
                return LanguageLine::getGroup($group, $locale);
            }
        );
    }

    protected function languageLinesAreAvailable(): bool
    {
        static $fragmentTableFound = null;

        if (is_null($fragmentTableFound)) {
            try {
                $fragmentTableFound = Schema::hasTable('language_lines');
            } catch (\Exception $e) {
                $fragmentTableFound = false;
            }
        }

        return $fragmentTableFound;
    }
}
