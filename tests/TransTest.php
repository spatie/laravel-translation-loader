<?php

namespace Spatie\TranslationLoader\Test;

use Illuminate\Support\Arr;

class TransTest extends TestCase
{
    protected $nested = [
        'bool' => [
            1 => 'Yes',
            0 => 'No',
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_translations_for_language_files()
    {
        $this->assertEquals('en value', trans('file.key'));
        $this->assertEquals('page not found', trans('file.404.title'));
        $this->assertEquals('This page does not exists', trans('file.404.message'));
    }

    /** @test */
    public function it_can_get_translations_for_language_files_for_the_current_locale()
    {
        app()->setLocale('nl');

        $this->assertEquals('nl value', trans('file.key'));
        $this->assertEquals('pagina niet gevonden', trans('file.404.title'));
        $this->assertEquals('Deze pagina bestaat niet', trans('file.404.message'));
    }

    /** @test */
    public function by_default_it_will_prefer_a_db_translation_over_a_file_translation()
    {
        $this->createLanguageLine('file', 'key', ['en' => 'en value from db']);
        $this->createLanguageLine('file', '404.title', ['en' => 'page not found from db']);

        $this->assertEquals('en value from db', trans('file.key'));
        $this->assertEquals('page not found from db', trans('file.404.title'));
        $this->assertEquals('This page does not exists', trans('file.404.message'));
    }

    /** @test */
    public function it_will_return_array_if_the_given_translation_is_nested()
    {
        foreach (Arr::dot($this->nested) as $key => $text) {
            $this->createLanguageLine('nested', $key, ['en' => $text]);
        }

        $this->assertEqualsCanonicalizing($this->nested['bool'], trans('nested.bool'), '$canonicalize = true', $delta = 0.0, $maxDepth = 10, $canonicalize = true);
    }

    /** @test */
    public function it_will_return_the_translation_string_if_max_nested_level_is_reached()
    {
        foreach (Arr::dot($this->nested) as $key => $text) {
            $this->createLanguageLine('nested', $key, ['en' => $text]);
        }

        $this->assertEquals($this->nested['bool'][1], trans('nested.bool.1'));
    }

    /** @test */
    public function it_will_return_the_dotted_translation_key_if_no_translation_found()
    {
        $notFoundKey = 'nested.bool.3';

        foreach (Arr::dot($this->nested) as $key => $text) {
            $this->createLanguageLine('nested', $key, ['en' => $text]);
        }

        $this->assertEquals($notFoundKey, trans($notFoundKey));
    }

    /** @test */
    public function it_will_default_to_fallback_if_locale_is_missing()
    {
        app()->setLocale('de');
        $this->createLanguageLine('missing_locale', 'key', ['en' => 'en value from db']);

        $this->assertEquals('en value from db', trans('missing_locale.key'));
    }
}
