<?php

namespace Spatie\TranslationLoader\Test;

use Illuminate\Support\Facades\Artisan;

class VendorTranslationsImporterTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_import_vendor_translations()
    {
        Artisan::call('laravel-translation-loader:vendor-import');
    }

    /** @test */
    public function it_can_get_translation_after_import()
    {
        Artisan::call('laravel-translation-loader:vendor-import');

        $this->assertEquals('en value', trans('file.key'));
    }
}
