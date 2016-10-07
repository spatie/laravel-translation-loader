<?php

namespace Spatie\DbLanguageLines;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class LanguageLine extends Model
{
    use HasTranslations {
        setTranslation as traitSetTranslation;
    }

    /** @var array */
    public $translatable = ['text'];

    /** @var array */
    public $guarded = ['id'];

    public $table = 'language_lines';

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
                ->pluck('text', 'key')
                ->toArray();
        });
    }

    protected function flushGroupCache()
    {
        foreach ($this->getTranslatedLocales('text') as $locale) {
            Cache::forget(static::getCacheKey($this->group, $locale));
        }
    }

    public static function getCacheKey(string $group, string $locale): string
    {
        return "spatie.laravel-db-language-lines.{$group}.{$locale}";
    }

    /**
     * @param string $locale
     * @param string $value
     *
     * @return $this
     */
    public function setTranslation(string $locale, string $value)
    {
        return $this->traitSetTranslation('text', $locale, $value);
    }
}
