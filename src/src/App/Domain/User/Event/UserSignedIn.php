<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\Email;
use App\Shared\Infrastructure\Persistence\ReadModel\Serializable;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserSignedIn implements Serializable
{
    public Email $email;

    public UuidInterface $uuid;

    public function __construct(UuidInterface $uuid, Email $email)
    {
        $this->uuid = $uuid;
        $this->email = $email;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'email');

        return new self(
            Uuid::fromString($data['uuid']),
            Email::fromString($data['email'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'email' => $this->email->toString(),
        ];
    }
}
