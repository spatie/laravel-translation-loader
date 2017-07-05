<?php

namespace Spatie\TranslationLoader\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Spatie\TranslationLoader\LanguageLine;

class ImportVendorTranslations extends Command
{
    const DS = DIRECTORY_SEPARATOR;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-translation-loader:import-vendor-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import vendor translations.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $translations = [];

        // For each locale
        $this->getLocales()->each(function ($locale) use (&$translations) {
            // And foreach vendor item
            foreach (config('laravel-translation-loader.vendor_import', []) as $file) {
                // Load the vendor translations
                $lines = array_dot(require_once(resource_path('lang' . self::DS . $locale . self::DS . $file . '.php')));

                // And assign them to a data array
                foreach ($lines as $key => $line) {
                    if ($line) {
                        $translations[$file][$key][$locale] = $line;
                    }
                }
            }
        });

        // Loop in data array and foreach line create new language line item
        foreach ($translations as $file => $lines) {
            foreach ($lines as $key => $line) {
                LanguageLine::create([
                    'group' => $file,
                    'key'   => $key,
                    'text'  => $line,
                ]);
            }
        }
    }

    /**
     * Find all defined locales. Will loop inside lang directory and get
     * top level locale directories to find all supported locales
     *
     * @return Collection
     */
    private function getLocales(): Collection
    {
        return collect(File::directories(resource_path('lang')))
            ->map(function ($path) {
                return str_replace(resource_path('lang') . self::DS, '', $path);
            });
    }
}
