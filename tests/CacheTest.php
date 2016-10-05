<?php

namespace Spatie\DbLanguageLines\Test;

use Illuminate\Support\Facades\DB;
use Illuminate\Translation\Translator;

class CacheTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
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

    protected function flushIlluminateTranslatorCache()
    {
        $loader = $this->app['translation.loader'];

        $locale = $this->app['config']['app.locale'];

        $trans = new Translator($loader, $locale);

        $trans->setFallback($this->app['config']['app.fallback_locale']);

        $this->app['translator'] = $trans;
    }
}
