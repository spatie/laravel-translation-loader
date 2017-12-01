<?php

namespace Spatie\TranslationLoader;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

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
        static::saved(function (LanguageLine $languageLine) {
            $languageLine->flushGroupCache();
        });

        static::deleted(function (LanguageLine $languageLine) {
            $languageLine->flushGroupCache();
        });
    }

    public static function getTranslationsForGroup(string $locale, string $group): array
    {
        return Cache::rememberForever(static::getCacheKey($group, $locale), function () use ($group, $locale) {
            return static::query()
                ->where('group', $group)
                ->get()
                ->reduce(function ($lines, LanguageLine $languageLine) use ($locale) {
                    array_set($lines, $languageLine->key, $languageLine->getTranslation($locale));

                    return $lines;
                }) ?? [];
        });
    }

    public static function getCacheKey(string $group, string $locale): string
    {
        return "spatie.translation-loader.{$group}.{$locale}";
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getTranslation(string $locale): string
    {
        return $this->text[$locale] ?? $this->text[config('app.fallback_locale')];
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

    protected function flushGroupCache()
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
