<?php

declare(strict_types=1);

namespace App\Domain\Partner\Event;

use App\Domain\Partner\ValueObject\Inn;
use App\Domain\User\Entity\User;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Shared\Infrastructure\Persistence\ReadModel\Serializable;

final class PartnerWasCreated implements Serializable
{
    public UuidInterface $uuid;
    public Inn $inn;
    public DateTime $createdAt;
    public User $user;

    public function __construct(
        UuidInterface $uuid,
        Inn $inn,
        DateTime $createdAt,
        $phone,
        $bik,
        $kpp,
        $bank,
        $bankAccount,
        $regionCode,
        $legalAddress,
        $actualAddress
    )
    {
        $this->uuid          = $uuid;
        $this->inn           = $inn;
        $this->phone         = $phone;
        $this->bik           = $bik;
        $this->kpp           = $kpp;
        $this->bank          = $bank;
        $this->bankAccount   = $bankAccount;
        $this->regionCode    = $regionCode;
        $this->legalAddress  = $legalAddress;
        $this->actualAddress = $actualAddress;
        $this->createdAt     = $createdAt;
    }

    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');

        return new self(
            Uuid::fromString($data['uuid']),
            new Inn($data['inn']),
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
