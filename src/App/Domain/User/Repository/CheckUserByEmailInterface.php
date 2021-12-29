<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\ValueObject\Email;

interface CheckUserByEmailInterface
{
    /**
     * @return array{0: \Ramsey\Uuid\UuidInterface, 1: Email, 2: \App\User\Domain\ValueObject\Auth\HashedPassword}
     */
    public function getCredentialsByEmail(Email $email): array;
}
