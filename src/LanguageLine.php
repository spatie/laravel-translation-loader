<?php

namespace Spatie\TranslationLoader;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class LanguageLine extends Model
{
    /** @var array */
    public $translatable = ['text'];

    /** @var array */
    public $guarded = ['id'];

    /** @var array */
    protected $casts = ['text' => 'array'];

    public static function boot()
    {
        parent::boot();

        $flushGroupCache = function (self $languageLine) {
            $languageLine->flushGroupCache();
        };

        static::saved($flushGroupCache);
        static::deleted($flushGroupCache);
    }

    public static function getTranslationsForGroup(string $locale, string $group, $namespace = null): array
    {
        return Cache::rememberForever(static::getCacheKey($group, $locale, $namespace), function () use ($namespace, $group, $locale) {
            return static::query()
                    ->when($namespace && $namespace != '*',
                        fn ($query) => $query->where('namespace', $namespace),
                        fn ($query) => $query->whereNull('namespace'),
                    )
                    ->where('group', $group)
                    ->get()
                    ->reduce(function ($lines, self $languageLine) use ($namespace, $group, $locale) {
                        $translation = $languageLine->getTranslation($locale);

                        if ($translation !== null && $group === '*') {
                            // Make a flat array when returning json translations
                            $lines[$languageLine->key] = $translation;
                        } elseif ($translation !== null && $group !== '*') {
                            // Make a nested array when returning normal translations
                            Arr::set($lines, $languageLine->key, $translation);
                        }

                        return $lines;
                    }) ?? [];
        });
    }

    public static function getCacheKey(string $group, string $locale, $namespace): string
    {
        $namespace ??= '*';
        return "spatie.translation-loader.{$namespace}.{$group}.{$locale}";
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getTranslation(string $locale): ?string
    {
        if (! isset($this->text[$locale])) {
            $fallback = config('app.fallback_locale');

            return $this->text[$fallback] ?? null;
        }

        return $this->text[$locale];
    }

    /**
     * @param string $locale
     * @param string $value
     *
     * @return $this
     */
    public function setTranslation(string $locale, string $value)
    {
        $this->text = array_merge($this->text ?? [], [$locale => $value]);

        return $this;
    }

    /**
     * @param string $namespace
     *
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace;

        return $this;
    }

    public function flushGroupCache()
    {
        foreach ($this->getTranslatedLocales() as $locale) {
            Cache::forget(static::getCacheKey($this->group, $locale, $this->namespace));
        }
    }

    protected function getTranslatedLocales(): array
    {
        return array_keys($this->text);
    }
}
