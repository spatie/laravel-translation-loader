<?php

namespace Spatie\TranslationLoader\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Translation\FileLoader;
use Spatie\TranslationLoader\LanguageLine;

class VendorTranslationsImporter extends Command implements TranslationsImporter
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-translation-loader:vendor-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import vendor translations.';

    /**
     * The translation file loader
     *
     * @var FileLoader
     */
    private $loader;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->loader = new FileLoader(app('files'), app('path.lang'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $translations = $this->getLocales()
            ->reduce(function ($groups, $locale) {
                foreach (config('laravel-translation-loader.vendor_import', []) as $group) {
                    foreach (array_dot($this->loader->load($locale, $group)) as $key => $line) {
                        if ($line) {
                            $groups[$group][$key][$locale] = $line;
                        }
                    }
                }

                return $groups;
            });

        $this->createLanguageLines($translations);
    }

    /**
     * Find all defined locales. Will loop inside lang directory and get
     * top level locale directories to find all supported locales
     *
     * @return Collection
     */
    private function getLocales(): Collection
    {
        return collect(File::directories(app('path.lang')))
            ->map(function ($path) {
                return str_replace(app('path.lang') . DIRECTORY_SEPARATOR, '', $path);
            });
    }

    /**
     * @param array $translations
     */
    private function createLanguageLines(array $translations)
    {
        foreach ($translations as $file => $lines) {
            foreach ($lines as $key => $line) {
                $this->createLanguageLine($file, $key, $line);
            }
        }
    }

    /**
     * Create single translation line
     *
     * @param string $group
     * @param string $key
     * @param array  $text
     */
    public function createLanguageLine(string $group, string $key, array $text)
    {
        LanguageLine::create(compact(['group', 'key', 'text']));
    }
}
