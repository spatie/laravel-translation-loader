<?php

return [

    /**
     * Language lines will be fetched by these loaders. You can put any class here that implements
     * the Spatie\DbLanguageLines\TranslationLoaders\TranslationLoader-interface.
     */
    'translationLoaders' => [
        Spatie\DbLanguageLines\TranslationLoaders\Db::class,
    ],

    /**
     * This is the model used by the Db Translation loader. You can put any model here
     * that extends Spatie\DbLanguageLines\LanguageLine.
     */
    'model' => Spatie\DbLanguageLines\LanguageLine::class,
];
