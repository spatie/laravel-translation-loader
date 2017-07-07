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

    /**
     * Get translations for group
     *
     * @param string $locale
     * @param string $group
     *
     * @return array
     * @see https://adamwathan.me/2016/07/14/customizing-keys-when-mapping-collections/
     */
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
        return "spatie.laravel-translation-loader.{$group}.{$locale}";
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getTranslation(string $locale): string
    {
        return $this->text[$locale] ?? '';
    }

    /**
     * @param string $locale
     * @param string $value
     */
    public function setTranslation(string $locale, string $value)
    {
        $this->text = array_merge($this->text ?? [], [$locale => $value]);
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
