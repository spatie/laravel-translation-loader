<?php

namespace Spatie\DbLanguageLines\Test;

class DbTransTest extends TestCase
{
    /** @var \Spatie\DbLanguageLines\LanguageLine */
    protected $languageLine;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_a_translation_for_the_current_app_locale()
    {
        $this->assertEquals('english', trans('group.key'));
    }

    /** @test */
    public function it_can_get_a_correct_translation_after_the_locale_has_been_changed()
    {
        app()->setLocale('nl');

        $this->assertEquals('nederlands', trans('group.key'));
    }

    /** @test */
    public function it_can_return_the_group_and_the_key_when_getting_a_non_existing_translation()
    {
        app()->setLocale('nl');

        $this->assertEquals('group.unknown', trans('group.unknown'));
    }

    /** @test */
    public function it_supports_placeholders()
    {
        $this->createTranslation('group', 'placeholder', ['en' => 'text with :placeholder']);

        $this->assertEquals(
            'text with filled in placeholder',
            trans('group.placeholder', ['placeholder' => 'filled in placeholder'])
        );
    }
}
