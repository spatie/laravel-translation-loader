<?php

namespace Spatie\DbLanguageLines;

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

        if (!$this->schemaHasTable('language_lines')) {
            return [];
        }

        return LanguageLine::getGroup($group, $locale);
    }

    protected function schemaHasTable(string $tableName): bool
    {
        static $tableFound = null;

        if (is_null($tableFound)) {
            try {
                $tableFound = Schema::hasTable($tableName);
            } catch (\Exception $e) {
                $tableFound = false;
            }
        }

        return $tableFound;
    }
}
