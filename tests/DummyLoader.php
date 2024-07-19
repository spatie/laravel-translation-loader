<?php

declare(strict_types=1);

namespace Spatie\TranslationLoader\Test;

use Spatie\TranslationLoader\TranslationLoaders\TranslationLoader;

class DummyLoader implements TranslationLoader
{
    public function loadTranslations(string $locale, string $group, string|null $namespace = null): array
    {
        return ['dummy' => 'this is dummy'];
    }
}
