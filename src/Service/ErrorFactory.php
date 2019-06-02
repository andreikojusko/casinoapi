<?php

namespace App\Service;

use App\Model\Error;

class ErrorFactory
{
    public function createError(string $space, int $code, ?array $replacements = null): Error
    {
        $error = new Error();
        $error->code = $code;
        $message = $space::LABELS[$code] ?? '';

        if ($replacements !== null) {
            $message = \str_replace(\array_keys($replacements), $replacements, $message);
        }
        $error->message = $message;

        return $error;
    }
}
