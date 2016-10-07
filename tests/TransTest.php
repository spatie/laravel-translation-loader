<?php

namespace Spatie\DbLanguageLines\Test;

class TransTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_translations_for_language_files()
    {
        $this->assertEquals('en value', trans('file.key'));
    }

    /** @test */
    public function it_can_get_translations_for_language_files_for_the_current_locale()
    {
        app()->setLocale('nl');

        $this->assertEquals('nl value', trans('file.key'));
    }

    /** @test */
    public function by_default_it_will_prefer_a_db_translation_over_a_file_translation()
    {
        $this->createTranslation('file', 'key', ['en' => 'en value from db']);

        $this->assertEquals('en value from db', trans('file.key'));
    }
}
