<?php

namespace Spatie\TranslationLoader;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Translation\FileLoader;
use Spatie\TranslationLoader\TranslationLoaders\TranslationLoader;
use Illuminate\Database\Eloquent\Model;
use Exception;

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

        if(!$this->hasValidDbRequirements()){
            return $fileTranslations;
        }

        if (! is_null($namespace) && $namespace !== '*') {
            return $fileTranslations;
        }

        $loaderTranslations = $this->getTranslationsForTranslationLoaders($locale, $group, $namespace);

        return array_replace_recursive($fileTranslations, $loaderTranslations);
    }

    protected function getTranslationsForTranslationLoaders(
        string $locale,
        string $group,
        string $namespace = null
    ): array {
        return collect(config('translation-loader.translation_loaders'))
            ->map(function (string $className) {
                return app($className);
            })
            ->mapWithKeys(function (TranslationLoader $translationLoader) use ($locale, $group, $namespace) {
                return $translationLoader->loadTranslations($locale, $group, $namespace);
            })
            ->toArray();
    }

    /**
     * Confirms that migration has been run and 
     * current context has a valid DB connection.
     */
    protected function hasValidDbRequirements() {
        $model = config('translation-loader.model');
        $model = new $model;
        if(!$model instanceof Model){
            return false;
        }

        try {
            DB::connection()->getPdo();
        } catch (Exception $e) {
            return false;
        }
        
        if( !Schema::hasTable($model->getTable()) ){
            return false;
        }

        return true;
    }
}
