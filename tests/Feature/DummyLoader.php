<?php

namespace Tests\Feature;

use Spatie\TranslationLoader\TranslationLoaders\TranslationLoader;

class DummyLoader implements TranslationLoader
{
    public function loadTranslations(string $locale, string $group, string|null $namespace = null): array
    {
        return ['dummy' => 'this is dummy'];
    }
}
