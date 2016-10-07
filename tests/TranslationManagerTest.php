<?php

namespace Spatie\DbLanguageLines\Test;

use Spatie\DbLanguageLines\TranslationLoaders\Db;

class TranslationManagerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_not_use_database_translations_if_the_provider_is_not_configured()
    {
        $this->app['config']->set('laravel-db-language-lines.translationLoaders', []);

        $this->assertEquals('group.key', trans('group.key'));
    }

    /** @test */
    public function it_will_merge_translation_from_all_providers()
    {
        $this->app['config']->set('laravel-db-language-lines.translationLoaders', [
            Db::class,
            DummyLoader::class,
        ]);

        $this->createTranslation('db', 'key', ['en' => 'db']);

        $this->assertEquals('db', trans('db.key'));
        $this->assertEquals('this is dummy', trans('dummy.dummy'));
    }
}
