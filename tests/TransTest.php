<?php

namespace Spatie\DbLanguageLines\Test;

use Illuminate\Support\Facades\DB;
use Illuminate\Translation\Translator;
use Spatie\DbLanguageLines\LanguageLine;

class TransTest extends TestCase
{
    /** @var \Spatie\DbLanguageLines\LanguageLine */
    protected $languageLine;

    public function setUp()
    {
        parent::setUp();

        $this->languageLine = $this->createTranslation('group','key',  ['en' => 'english', 'nl' => 'nederlands']);
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
    public function it_will_cache_all_translations()
    {
        trans('group.key');

        $queryCount = count(DB::getQueryLog());
        $this->flushIlluminateTranslatorCache();

        trans('group.key');

        $this->assertEquals($queryCount, count(DB::getQueryLog()));
    }

    /** @test */
    public function it_can_get_an_updated_translation()
    {
        trans('group.key');

        $this->languageLine->setTranslation('en', 'updated');
        $this->languageLine->save();

        $this->flushIlluminateTranslatorCache();

        $this->assertEquals('updated', trans('group.key'));
    }

    /** @test */
    public function it_will_not_be_able_to_translate_a_key_when_the_translation_is_deleted()
    {
        $this->assertEquals('english', trans('group.key'));

        $this->languageLine->delete();
        $this->flushIlluminateTranslatorCache();

        $this->assertEquals('group.key', trans('group.key'));
    }

    protected function createTranslation(string $group, string $key, array $text): LanguageLine
    {
        return LanguageLine::create(compact('group', 'key', 'text'));
    }

    protected function flushIlluminateTranslatorCache()
    {
        $app = app();

        $loader = $app['translation.loader'];

        $locale = $app['config']['app.locale'];

        $trans = new Translator($loader, $locale);

        $trans->setFallback($app['config']['app.fallback_locale']);

        $app['translator'] = $trans;
    }
}
