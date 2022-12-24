<?php

beforeEach(function () {
    $this->TERM1 = 'file not found';
    $this->TERM1_EN = 'File not found';
    $this->TERM1_NL = 'Bestand niet gevonden';
    $this->TERM1_EN_DB = 'File not found from db';
    $this->TERM1_NL_DB = 'File not found from db';
    $this->TERM2 = 'file not found. it might be in trash.';
    $this->TERM2_EN = 'File not found. It might be in trash.';
    $this->TERM2_NL = 'Bestand niet gevonden. Het bestand is waarschijnlijk verwijderd.';
    $this->TERM2_EN_DB = 'File not found from db. It might be in trash.';
    $this->TERM2_NL_DB = 'Bestand niet gevonden uit de database. Het bestand is waarschijnlijk verwijderd.';
});

it('can get translations for language files', function () {
    expect(__($this->TERM1))->toEqual($this->TERM1_EN)
        ->and(__($this->TERM2))->toEqual($this->TERM2_EN);
});

it('can get translations for language files for the current locale', function () {
    app()->setLocale('nl');

    expect(__($this->TERM1))->toEqual($this->TERM1_NL)
        ->and(__($this->TERM2))->toEqual($this->TERM2_NL);
});

it('by default it will prefer a db translation over a file translation', function () {
    $this->createLanguageLine('*', $this->TERM1, ['en' => $this->TERM1_EN_DB]);
    $this->createLanguageLine('*', $this->TERM2, ['en' => $this->TERM2_EN_DB]);

    expect(__($this->TERM1))->toEqual($this->TERM1_EN_DB)
        ->and(__($this->TERM2))->toEqual($this->TERM2_EN_DB);
});

it('will default to fallback if locale is missing', function () {
    app()->setLocale('de');
    $this->createLanguageLine('*', $this->TERM1, ['en' => $this->TERM1_EN_DB]);

    expect(__($this->TERM1))->toEqual($this->TERM1_EN_DB);
});
