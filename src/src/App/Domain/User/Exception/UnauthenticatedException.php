<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

class UnauthenticatedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Unauthenticated', 401);
    }
}
