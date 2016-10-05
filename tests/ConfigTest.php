<?php

namespace Spatie\DbLanguageLines\Test;

use Spatie\DbLanguageLines\Exceptions\InvalidConfiguration;
use Spatie\DbLanguageLines\LanguageLine;

class ConfigTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_work_with_a_custom_model()
    {
        $alternativeModel = new class extends LanguageLine {
            public static function getGroup(string $group, string $locale): array
            {
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
}
