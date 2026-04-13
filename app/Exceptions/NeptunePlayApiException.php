<?php

namespace App\Exceptions;

use App\Enums\NeptunePlayErrorCode;
use Exception;

class NeptunePlayApiException extends Exception
{
    public function __construct(
        public readonly NeptunePlayErrorCode $errorCode,
        public readonly array $responseBody = [],
        string $message = '',
    ) {
        parent::__construct($message ?: $errorCode->message(), $errorCode->value);
    }
}
