<?php

return [

    /**
     * Language lines will be fetched by these loaders.
     */
    'translationLoaders' => [
        Spatie\DbLanguageLines\TranslationLoaders\PhpFile::class,
        Spatie\DbLanguageLines\TranslationLoaders\Db::class,
    ],

    'model' => Spatie\DbLanguageLines\LanguageLine::class,
];
