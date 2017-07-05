<?php

return [

    /*
     * Language lines will be fetched by these loaders. You can put any class here that implements
     * the Spatie\TranslationLoader\TranslationLoaders\TranslationLoader-interface.
     */
    'translation_loaders' => [
        Spatie\TranslationLoader\TranslationLoaders\Db::class,
    ],

    /*
     * This is the model used by the Db Translation loader. You can put any model here
     * that extends Spatie\TranslationLoader\LanguageLine.
     */
    'model' => Spatie\TranslationLoader\LanguageLine::class,

    /*
     * List of vendor language files that will be imported via
     * laravel-translation-loader:import-vendor-translations command
     */
    'vendor_import' => [
        'auth',
        'pagination',
        'passwords',
        'validation',
    ],
];
