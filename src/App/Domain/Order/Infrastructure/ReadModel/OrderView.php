<?php

declare(strict_types=1);

namespace App\Domain\Order\Infrastructure\ReadModel;

use App\Domain\Shared\Infrastructure\ReadModel\SerializableReadModel;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\Domain\User\ValueObject\Credentials;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\Email;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;

class OrderView implements SerializableReadModel
{
    public const TYPE = 'OrderView';

    private UuidInterface $uuid;

    private string $description;

    private string $status;

    private DateTime $createdAt;

    private ?DateTime $updatedAt;

    private function __construct(
        UuidInterface $uuid,
        string $description,
        DateTime $createdAt,
        ?DateTime $updatedAt
    ) {
        $this->uuid = $uuid;
        $this->description = $description;
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
     * @return OrderView
     */
    public static function deserialize(array $data): self
    {
        return new self(
            Uuid::fromString($data['uuid']),
            $data['status'],
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
