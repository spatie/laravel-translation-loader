<?php

namespace Spatie\DbLanguageLines\Test\TranslationLoaders;

use DB;
use Illuminate\Translation\Translator;
use Spatie\DbLanguageLines\Exceptions\InvalidConfiguration;
use Spatie\DbLanguageLines\LanguageLine;
use Spatie\DbLanguageLines\Test\TestCase;

class DbTest extends TestCase
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
        $this->assertEquals('group.new', trans('group.new'));

        $this->createTranslation('group', 'new', ['en' => 'created']);
        $this->flushIlluminateTranslatorCache();

        $this->assertEquals('created', trans('group.new'));
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

    /** @test */
    public function it_can_work_with_a_custom_model()
    {
        $alternativeModel = new class extends LanguageLine {
            public static function getGroup(string $group, string $locale): array
            {
                dd('in anonymous');
                return ['key' => 'alternative class'];
            }
        };

        $this->app['config']->set('laravel-db-language-lines.model', get_class($alternativeModel));

        $this->assertEquals('alternative class', trans('group.key'));
    }

    /** @test */
    public function it_will_throw_an_exception_if_the_configured_model_does_not_extend_the_default_one()
    {
        $invalidModel = new class {
        };

        $this->app['config']->set('laravel-db-language-lines.model', get_class($invalidModel));

        $this->expectException(InvalidConfiguration::class);

        $this->assertEquals('alternative class', trans('group.key'));
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
