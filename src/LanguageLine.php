<?php

namespace Spatie\DbLanguageLines;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LanguageLine extends Model
{
    use HasTranslations;

    public $translatable = ['text'];

    public $guarded = ['id'];

    /**
     * @param string $name
     *
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
            ->where('group', $group)
            ->get()
            ->pluck('text', 'key')
            ->toArray();
    }
}
