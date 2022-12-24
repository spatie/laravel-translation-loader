<?php

use Spatie\TranslationLoader\LanguageLine;

it('can get a translation', function () {
    $languageLine = $this->createLanguageLine('group', 'new', ['en' => 'english', 'nl' => 'nederlands']);

    expect($languageLine->getTranslation('en'))->toEqual('english')
        ->and($languageLine->getTranslation('nl'))->toEqual('nederlands');
});

it('can set a translation', function () {
    $languageLine = $this->createLanguageLine('group', 'new', ['en' => 'english']);

    $languageLine->setTranslation('nl', 'nederlands');

    expect($languageLine->getTranslation('en'))->toEqual('english')
        ->and($languageLine->getTranslation('nl'))->toEqual('nederlands');
});

it('can set a translation on a fresh model', function () {
    $languageLine = new LanguageLine();

    $languageLine->setTranslation('nl', 'nederlands');

    expect($languageLine->getTranslation('nl'))->toEqual('nederlands');
});

it('doesnt show error when getting nonexistent translation', function () {
    $languageLine = $this->createLanguageLine('group', 'new', ['nl' => 'nederlands']);
    expect($languageLine->getTranslation('en'))->toBe(null);
});

it('get fallback locale if doesnt exists', function () {
    $languageLine = $this->createLanguageLine('group', 'new', ['en' => 'English']);
    expect($languageLine->getTranslation('es'))->toEqual('English');
});
