<?php

namespace Spatie\TranslationLoader\Test;

use Spatie\TranslationLoader\TranslationLoaders\Db;

class TranslationManagerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_not_use_database_translations_if_the_provider_is_not_configured()
    {
        $this->app['config']->set('translation-loader.translation_loaders', []);

        $this->assertEquals('group.key', trans('group.key'));
    }

    /** @test */
    public function it_will_merge_translation_from_all_providers()
    {
        $this->app['config']->set('translation-loader.translation_loaders', [
            Db::class,
            DummyLoader::class,
        ]);

        $this->createLanguageLine('db', 'key', ['en' => 'db']);

        $this->assertEquals('db', trans('db.key'));
        $this->assertEquals('this is dummy', trans('dummy.dummy'));
    }
}
