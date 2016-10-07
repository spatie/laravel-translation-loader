<?php

namespace Spatie\TranslationLoader\Test;

use Spatie\TranslationLoader\TranslationLoaders\TranslationLoader;

class DummyLoader implements TranslationLoader
{
    public function loadTranslations(string $locale, string $group, string $namespace = null): array
    {
        return ['dummy' => 'this is dummy'];
    }
}
