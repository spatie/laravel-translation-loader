<?php

namespace Spatie\DbLanguageLines\TranslationLoaders;

use Illuminate\Translation\FileLoader;

class PhpFile extends FileLoader implements TranslationLoader
{
    public function loadTranslations(string $locale, string $group, string $namespace = null): array
    {
        if (! is_null($namespace) && $namespace !== '*') {
            return $this->loadNamespaced($locale, $group, $namespace);
        }

        return $this->loadPath($this->path, $locale, $group);
    }
}
