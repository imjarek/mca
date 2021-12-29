<?php

declare(strict_types=1);

namespace App\Domain\User\Application\Command\SignUp;

use App\Shared\Application\Command\CommandInterface;
use App\Domain\User\ValueObject\Credentials;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\Email;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class SignUpCommand implements CommandInterface
{
    /** @psalm-readonly */
    public UuidInterface $uuid;

    /** @psalm-readonly */
    public Credentials $credentials;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(UuidInterface $uuid, string $email, string $plainPassword)
    {
        $this->uuid = $uuid;
        $this->credentials = new Credentials(Email::fromString($email), HashedPassword::encode($plainPassword));
    }
}
