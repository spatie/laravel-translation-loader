<?php

namespace Spatie\DbLanguageLines\Test;

use Illuminate\Support\Facades\DB;
use Illuminate\Translation\Translator;
use Spatie\DbLanguageLines\LanguageLine;

class CacheTest extends TestCase
{
    /** @var \Spatie\DbLanguageLines\LanguageLine */
    protected $languageLine;

    public function setUp()
    {
        parent::setUp();

        $this->languageLine = $this->createTranslation('group','key',  ['en' => 'english', 'nl' => 'nederlands']);
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
    public function it_flushes_the_cache_when_a_translation_has_been_created()
    {
        $this->assertEquals('group.new',trans('group.new'));

        $this->createTranslation('group', 'new', ['en' => 'created']);
        $this->flushIlluminateTranslatorCache();

        $this->assertEquals('created',trans('group.new'));
    }

    /** @test */
    public function it_flushes_the_cache_when_a_translation_has_been_updated()
    {
        trans('group.key');

        $this->languageLine->setTranslation('en', 'updated');
        $this->languageLine->save();

        $this->flushIlluminateTranslatorCache();

        $this->assertEquals('updated', trans('group.key'));
    }

    /** @test */
    public function it_flushes_the_cache_when_a_translation_has_been_deleted()
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
