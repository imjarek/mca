<?php

declare(strict_types=1);

namespace App\Domain\Partner\Application\Command;

use App\Domain\Partner\Specification\UniqueInnSpecification;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DateTimeException;
use App\Domain\Partner\Repository\PartnerRepositoryInterface;
use App\Domain\Partner\Specification\UniqueInnSpecificationInterface;
use App\Domain\Partner\Entity\Partner;

final class CreatePartnerHandler implements CommandHandlerInterface
{
    private PartnerRepositoryInterface $partnerRepository;

    private UniqueInnSpecification $uniqueInnSpecification;

    public function __construct(
        PartnerRepositoryInterface $partnerRepository,
        UniqueInnSpecificationInterface $uniqueInnSpecification
    ) {
        $this->partnerRepository = $partnerRepository;
        $this->uniqueInnSpecification = $uniqueInnSpecification;
    }

    /**
     * @throws DateTimeException
     */
    public function __invoke(CreatePartnerCommand $command): void
    {
        $partner = Partner::create(
            $command->uuid,
            $command->inn,
            $command->phone,
            $command->bik,
            $command->kpp,
            $command->bank,
            $command->bankAccount,
            $command->legalAddress,
            $command->regionCode,
            $command->actualAdress,
            $this->uniqueInnSpecification
        );

        $this->partnerRepository->store($partner);
    }
}
