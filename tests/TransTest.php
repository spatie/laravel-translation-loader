<?php

namespace Spatie\DbLanguageLines\Test;

use Spatie\DbLanguageLines\LanguageLine;

class TransTest extends TestCase
{
    /** @test */
    public function it_can_get_a_translation_for_the_current_app_locale()
    {
        $this->createTranslation('group','key',  ['en' => 'english', 'nl' => 'nederlands']);

        $this->assertEquals('english', trans('group.key'));
    }

    protected function createTranslation(string $group, string $key, array $text)
    {
        LanguageLine::create(compact('group', 'key', 'text'));
    }
}
