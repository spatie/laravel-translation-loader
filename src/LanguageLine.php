<?php

namespace Spatie\TranslationLoader;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDOException;

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

    public static function getTranslationsForGroup(string $locale, string $group, string|null $namespace = null): array
    {
        // When the app uses laravel-sail the package breaks every artisan command ran outside sail context.
        // That's beacuse artisan starts an app and registers all service providers,
        // but the cache store and/or database is unavailable beacause the hostname
        // (e.g. redis/mysql) is unresolvable.
        try {
            DB::connection()->getPdo();
            Cache::get('laravel-translation-loader');
        } catch (PDOException $exception) {
            Log::error('laravel-translation-loader: Could not connect to the database, falling back to file translations');

            return [];
        } catch (RedisException $exception) {
            Log::error('laravel-translation-loader: Could not connect to the cache store, falling back to file translations');

            return [];
        }

        return Cache::rememberForever(static::getCacheKey($namespace, $group, $locale), function () use ($namespace, $group, $locale) {
            return static::query()
                ->where('namespace', $namespace)
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

    public static function getCacheKey(string $namespace, string $group, string $locale): string
    {
        return "spatie.translation-loader.$namespace.$group.$locale";
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
            Cache::forget(static::getCacheKey($this->namespace, $this->group, $locale));
        }
    }

    protected function getTranslatedLocales(): array
    {
        return array_keys($this->text);
    }
}
