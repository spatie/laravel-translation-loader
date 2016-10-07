<?php

namespace Spatie\DbLanguageLines\TranslationLoaders;

use Cache;
use Schema;
use Spatie\DbLanguageLines\Exceptions\InvalidConfiguration;
use Spatie\DbLanguageLines\LanguageLine;

class Db implements TranslationLoader
{
    public function loadTranslations(string $locale, string $group, string $namespace = null): array
    {
        $model = $this->getConfiguredModelClass();

        return $model::getTranslationsForGroup($locale, $group);
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
