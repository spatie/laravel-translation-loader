<?php

namespace Spatie\DbLanguageLines\LanguageLine;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LanguageLine extends Model
{
    use HasTranslations;

    public $translatable = ['text'];

    /**
     * @param string $name
     * @return \Spatie\DbLanguageLines\LanguageLine|null
     */
    public static function findByName(string $name)
    {
        return app('cache')->rememberForever("spatie.laravel-db-language-lines.findByName.{$name}", function () use ($name) {
            return static::where('name', $name)->first();
        });
    }

    public static function getGroup(string $group, string $locale): array
    {
        return static::query()
            ->where('name', 'LIKE', "{$group}.%")
            ->get()
            ->map(function (Fragment $fragment) use ($locale, $group) {
                return [
                    'key' => preg_replace("/{$group}\\./", '', $fragment->name, 1),
                    'text' => $fragment->translate('text', $locale),
                ];
            })
            ->pluck('text', 'key')
            ->toArray();
    }
}
