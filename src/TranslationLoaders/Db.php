<?php

namespace Spatie\DbLanguageLines\TranslationLoaders;

use Cache;
use Schema;
use Spatie\DbLanguageLines\Exceptions\InvalidConfiguration;

class Db implements TranslationLoader
{
    public function loadTranslations(string $locale, string $group, string $namespace = null): array
    {
        $model = app(config('laravel-db-language-lines.model'));

        if (!$this->schemaHasTable($model->getTable())) {
            return [];
        }

        return Cache::rememberForever($model::getCacheKey($group, $locale), function () use ($group, $locale, $model) {
            return $model::query()
                ->where('group', $group)
                ->get()
                ->pluck('text', 'key')
                ->toArray();
        });
    }

    protected function schemaHasTable(string $tableName): bool
    {
        static $tableFound = null;

        if (is_null($tableFound)) {
            try {
                $tableFound = Schema::hasTable($tableName);
            } catch (Exception $e) {
                $tableFound = false;
            }
        }

        return $tableFound;
    }

    protected function getConfiguredModelClass(): string
    {
        $modelClass = config('laravel-db-language-lines.model');

        if (!is_a(new $modelClass, LanguageLine::class)) {
            throw InvalidConfiguration::invalidModel($modelClass);
        }

        return $modelClass;
    }
}
