<?php

namespace App\Shared\Infrastructure\Service;

use App\Domain\User\Entity\User;

interface IEmailVerificationService
{
    public function sendEmailVerificationEmail(User $user): void;
}