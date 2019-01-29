<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

class Unauthorized extends \Exception
{
    public function __construct(
        string $message = '', int $code = 0, Throwable $previous = null
    ) {
        if (0 === $code) {
            $code = 401;
        }

        parent::__construct($message, $code, $previous);
    }
}
