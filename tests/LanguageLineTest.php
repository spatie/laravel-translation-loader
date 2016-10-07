<?php

namespace Spatie\TranslationLoader\Test;

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
}
