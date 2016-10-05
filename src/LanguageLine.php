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

    public $translatable = ['text'];

    public $guarded = ['id'];

    public static function boot()
    {
        static::saved(function (LanguageLine $languageLine) {
            foreach ($languageLine->getTranslatedLocales('text') as $locale) {
                Cache::forget(static::getCacheKey($languageLine->group, $locale));
            }
        });
    }

    public static function getGroup(string $group, string $locale): array
    {
        return Cache::rememberForever(static::getCacheKey($group, $locale), function () use ($group, $locale) {
            return static::query()
                ->where('group', $group)
                ->get()
                ->pluck('text', 'key')
                ->toArray();
        });
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

    public static function getCacheKey(string $group, string $locale)
    {


        return "spatie.laravel-db-language-lines.{$group}.{$locale}";
    }
}
