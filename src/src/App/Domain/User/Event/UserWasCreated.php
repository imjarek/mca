<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\Domain\User\ValueObject\Credentials;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\Email;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Shared\Infrastructure\Persistence\ReadModel\Serializable;
use Symfony\Contracts\EventDispatcher\Event;

final class UserWasCreated extends Event implements Serializable
{
    public UuidInterface $uuid;

    public Credentials $credentials;

    public DateTime $createdAt;

    public function __construct(UuidInterface $uuid, Credentials $credentials, DateTime $createdAt)
    {
        $this->uuid = $uuid;
        $this->credentials = $credentials;
        $this->createdAt = $createdAt;
    }

    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'credentials');

        return new self(
            Uuid::fromString($data['uuid']),
            new Credentials(
                Email::fromString($data['credentials']['email']),
                HashedPassword::fromHash($data['credentials']['password'])
            ),
            DateTime::fromString($data['created_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'credentials' => [
                'email' => $this->credentials->getEmail()->toString(),
                'password' => $this->credentials->getPassword()->toString(),
            ],
            'created_at' => $this->createdAt->toString(),
        ];
    }
}
