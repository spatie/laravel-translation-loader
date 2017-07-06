<?php

namespace Spatie\TranslationLoader\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Spatie\TranslationLoader\LanguageLine;

class VendorTranslationsImporter extends Command implements TranslationsImporter
{
    const DS = DIRECTORY_SEPARATOR;

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
        $translations = $this->getLocales()
            ->reduce(function ($translations, $path) {
                // And foreach vendor item from config
                foreach (config('laravel-translation-loader.vendor_import', []) as $file) {
                    $filePath = $this->getLocaleFilePath($path, $file);

                    // Only if the file exists
                    if (File::exists($filePath)) {
                        foreach (array_dot(require_once($filePath)) as $key => $line) {
                            $translations[$file][$key][$this->getLocale($path)] = $line;
                        }
                    } else {
                        $this->error("{$file} specified in config can not be loaded from: {$filePath}");
                    }
                }

                return $translations;
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
        return collect(File::directories(resource_path('lang')));
    }

    /**
     * Get locale from translation path
     *
     * @param string $path
     *
     * @return string
     */
    private function getLocale(string $path): string
    {
        return str_replace(resource_path('lang') . self::DS, '', $path);
    }

    /**
     * @param string $path
     * @param string $file
     *
     * @return string
     */
    private function getLocaleFilePath(string $path, string $file): string
    {
        return $path . self::DS . $file . '.php';
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
