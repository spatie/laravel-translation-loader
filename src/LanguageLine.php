<?php

namespace Spatie\TranslationLoader;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class LanguageLine extends Model
{
    /** @var array */
    public array $translatable = [
        'text',
    ];

    /** @var array<string> */
    public $guarded = [
        'id',
    ];

    /** @var array */
    protected $casts = [
        'text' => 'array',
    ];

    public static function boot(): void
    {
        parent::boot();

        $flushGroupCache = function (self $languageLine) {
            $languageLine->flushGroupCache();
        };

        static::saved($flushGroupCache);
        static::deleted($flushGroupCache);
    }

    public static function getTranslationsForGroup(string $locale, string $group): array
    {
        return Cache::rememberForever(static::getCacheKey($group, $locale), function () use ($group, $locale) {
            return static::query()
                ->where('group', $group)
                ->get()
                ->reduce(function ($lines, self $languageLine) use ($locale, $group) {
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

    public static function getCacheKey(string $group, string $locale): string
    {
        return "spatie.translation-loader.{$group}.{$locale}";
    }

    public function getTranslation(string $locale): string|null
    {
        if (! isset($this->text[$locale])) {
            $fallback = config('app.fallback_locale');

            return $this->text[$fallback] ?? null;
        }

        return $this->text[$locale];
    }

    public function setTranslation(string $locale, string $value): static
    {
        $this->text = array_merge($this->text ?? [], [$locale => $value]);

        return $this;
    }

    public function flushGroupCache(): void
    {
        foreach ($this->getTranslatedLocales() as $locale) {
            Cache::forget(static::getCacheKey($this->group, $locale));
        }
    }

    protected function getTranslatedLocales(): array
    {
        return array_keys($this->text);
    }
}
