<?php

declare(strict_types=1);

namespace App\Domain\Partner\Application\Command;

use App\Domain\User\Entity\User;
use App\Shared\Application\Command\CommandInterface;
use App\Domain\Partner\ValueObject\Inn;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CreatePartnerCommand implements CommandInterface
{
    /** @psalm-readonly */
    public UuidInterface $uuid;
    public Inn $inn;
    public User $user;
    /**
     * @throws AssertionFailedException
     */
    public function __construct(
        UuidInterface $uuid,
        string $inn,
        string $phone,
        string $bik,
        string $kpp,
        string $bank,
        string $bankAccount,
        string $regionCode,
        string $legalAddress,
        string $actualAdress,
        User $user
    ) {
        $this->uuid = $uuid;
        $this->inn = new Inn($inn);
        $this->phone = $phone;
        $this->bik = $bik;
        $this->kpp = $kpp;
        $this->bank = $bank;
        $this->bankAccount = $bankAccount;
        $this->regionCode  = $regionCode;
        $this->legalAddress = $legalAddress;
        $this->actualAdress = $actualAdress;
        $this->user = $user;
    }
}
