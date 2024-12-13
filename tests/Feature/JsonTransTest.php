<?php

beforeEach(function () {
    $this->term1 = 'file not found';
    $this->term1En = 'File not found';
    $this->term1Nl = 'Bestand niet gevonden';
    $this->term1EnDb = 'File not found from db';
    $this->term2 = 'file not found. it might be in trash.';
    $this->term2En = 'File not found. It might be in trash.';
    $this->term2Nl = 'Bestand niet gevonden. Het bestand is waarschijnlijk verwijderd.';
    $this->term2EnDb = 'File not found from db. It might be in trash.';
});

it('can get translations for language files', function () {
    expect(__($this->term1))->toEqual($this->term1En)
        ->and(__($this->term2))->toEqual($this->term2En);
});

it('can get translations for language files for the current locale', function () {
    app()->setLocale('nl');

    expect(__($this->term1))->toEqual($this->term1Nl)
        ->and(__($this->term2))->toEqual($this->term2Nl);
});

it('it will prefer a db translation over a file translation by default', function () {
    createLanguageLine('*', '*', $this->term1, ['en' => $this->term1EnDb]);
    createLanguageLine('*', '*', $this->term2, ['en' => $this->term2EnDb]);

    expect(__($this->term1))->toEqual($this->term1EnDb)
        ->and(__($this->term2))->toEqual($this->term2EnDb);
});

it('will default to fallback if locale is missing', function () {
    app()->setLocale('de');
    createLanguageLine('*', '*', $this->term1, ['en' => $this->term1EnDb]);

    expect(__($this->term1))->toEqual($this->term1EnDb);
});
