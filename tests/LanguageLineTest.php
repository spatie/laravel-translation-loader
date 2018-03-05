<?php

namespace Spatie\TranslationLoader\Test;

use Spatie\TranslationLoader\LanguageLine;

class LanguageLineTest extends TestCase
{
    /** @test */
    public function it_can_get_a_translation()
    {
        $languageLine = $this->createLanguageLine('group', 'new', ['en' => 'english', 'nl' => 'nederlands']);

        $this->assertEquals('english', $languageLine->getTranslation('en'));
        $this->assertEquals('nederlands', $languageLine->getTranslation('nl'));
    }

    /** @test */
    public function it_can_set_a_translation()
    {
        $languageLine = $this->createLanguageLine('group', 'new', ['en' => 'english']);

        $languageLine->setTranslation('nl', 'nederlands');

        $this->assertEquals('english', $languageLine->getTranslation('en'));
        $this->assertEquals('nederlands', $languageLine->getTranslation('nl'));
    }

    /** @test */
    public function it_can_set_a_translation_on_a_fresh_model()
    {
        $languageLine = new LanguageLine();

        $languageLine->setTranslation('nl', 'nederlands');

        $this->assertEquals('nederlands', $languageLine->getTranslation('nl'));
    }

    /** @test */
    public function it_doesnt_show_error_when_getting_unnexisted_translation()
    {
        $languageLine = $this->createLanguageLine('group', 'new', ['nl' => 'nederlands']);
        $this->assertEquals('', $languageLine->getTranslation('en'));
    }

    /** @test */
    public function get_fallback_locale_if_doesnt_exists()
    {
        $languageLine = $this->createLanguageLine('group', 'new', ['en' => 'English']);
        $this->assertEquals('English', $languageLine->getTranslation('es'));
    }

    /** @test */
    public function dont_return_arrays_if_was_set()
    {
        $value = [
            'max' => 'Maximo',
            'min' => 'Minimo'
        ];
        $languageLine = $this->createLanguageLine('validation', 'attributes', ['es' => $value]);
        $this->assertEquals('', $languageLine->getTranslation('es'));
    }
}
