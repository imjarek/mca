<?php

declare(strict_types=1);

namespace App\Domain\Partner\Infrastructure\ReadModel;

use App\Domain\User\ValueObject\Inn;
use App\Shared\Infrastructure\Persistence\ReadModel\SerializableReadModel;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\Domain\User\ValueObject\Email;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PartnerView implements SerializableReadModel
{
    public const TYPE = 'PartnerView';

    private UuidInterface $uuid;

    private Inn $inn;

    private DateTime $createdAt;

    private ?DateTime $updatedAt;

    private function __construct(
        UuidInterface $uuid,
        Inn $inn,
        DateTime $createdAt,
        ?DateTime $updatedAt
    ) {
        $this->uuid = $uuid;
        $this->inn = $inn;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     *
     * @return UserView
     */
    public static function deserialize(array $data): self
    {
        return new self(
            Uuid::fromString($data['uuid']),
            Email::fromString($data['inn']),
            DateTime::fromString($data['created_at']),
            isset($data['updated_at']) ? DateTime::fromString($data['updated_at']) : null
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->getId(),
            'credentials' => [
                'email' => (string) $this->credentials->email,
            ],
        ];
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function email(): string
    {
        return (string) $this->credentials->email;
    }

    public function changeEmail(Email $email): void
    {
        $this->credentials->email = $email;
    }

    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }
}
