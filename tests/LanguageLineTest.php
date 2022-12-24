<?php

use Spatie\TranslationLoader\LanguageLine;

it('can get a translation', function () {
    $languageLine = $this->createLanguageLine('group', 'new', ['en' => 'english', 'nl' => 'nederlands']);

    $this->assertEquals('english', $languageLine->getTranslation('en'));
    $this->assertEquals('nederlands', $languageLine->getTranslation('nl'));
});

it('can set a translation', function () {
    $languageLine = $this->createLanguageLine('group', 'new', ['en' => 'english']);

    $languageLine->setTranslation('nl', 'nederlands');

    $this->assertEquals('english', $languageLine->getTranslation('en'));
    $this->assertEquals('nederlands', $languageLine->getTranslation('nl'));
});

it('can set a translation on a fresh model', function () {
    $languageLine = new LanguageLine();

    $languageLine->setTranslation('nl', 'nederlands');

    $this->assertEquals('nederlands', $languageLine->getTranslation('nl'));
});

it('doesnt show error when getting nonexistent translation', function () {
    $languageLine = $this->createLanguageLine('group', 'new', ['nl' => 'nederlands']);
    $this->assertSame(null, $languageLine->getTranslation('en'));
});

it('get fallback locale if doesnt exists', function () {
    $languageLine = $this->createLanguageLine('group', 'new', ['en' => 'English']);
    $this->assertEquals('English', $languageLine->getTranslation('es'));
});
