<?php

declare(strict_types=1);

const TERM1 = 'file not found';

const TERM1_EN = 'File not found';

const TERM1_NL = 'Bestand niet gevonden';

const TERM1_EN_DB = 'File not found from db';

const TERM2 = 'file not found. it might be in trash.';

const TERM2_EN = 'File not found. It might be in trash.';

const TERM2_NL = 'Bestand niet gevonden. Het bestand is waarschijnlijk verwijderd.';

const TERM2_EN_DB = 'File not found from db. It might be in trash.';

it('can get translations for language files', function () {
    expect(__(TERM1))->toEqual(TERM1_EN)
        ->and(__(TERM2))->toEqual(TERM2_EN);
});

it('can get translations for language files for the current locale', function () {
    app()->setLocale('nl');

    expect(__(TERM1))->toEqual(TERM1_NL)
        ->and(__(TERM2))->toEqual(TERM2_NL);
});

test('by default it will prefer a db translation over a file translation', function () {
    createLanguageLine('*', TERM1, ['en' => TERM1_EN_DB]);
    createLanguageLine('*', TERM2, ['en' => TERM2_EN_DB]);

    expect(__(TERM1))->toEqual(TERM1_EN_DB)
        ->and(__(TERM2))->toEqual(TERM2_EN_DB);
});

it('will default to fallback if locale is missing', function () {
    app()->setLocale('de');
    createLanguageLine('*', TERM1, ['en' => TERM1_EN_DB]);

    expect(__(TERM1))->toEqual(TERM1_EN_DB);
});
