<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class EmailVerificationException extends \InvalidArgumentException implements VerifyEmailExceptionInterface
{
    public function getReason(): string
    {
        return 'Verification failed';
    }
}
