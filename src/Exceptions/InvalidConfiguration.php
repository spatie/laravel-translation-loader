<?php

namespace Spatie\DbLanguageLines\Exceptions;

use Exception;
use Spatie\DbLanguageLines\LanguageLine;

class InvalidConfiguration extends Exception
{
    public static function invalidModel(string $className)
    {
        return new static("You have configured an invalid class `{$className}`. A valid class extends ".LanguageLine::class.'.');
    }
}
