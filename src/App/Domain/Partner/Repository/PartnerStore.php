<?php

declare(strict_types=1);

namespace App\Domain\Partner\Repository;

use App\Domain\Partner\Entity\Partner;
use App\Shared\Infrastructure\Persistence\ReadModel\Repository\SqlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class PartnerStore extends SqlRepository implements PartnerRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    protected function setEntityManager(): void
    {
    }

    public function store(Partner $partner): void
    {
        $this->register($partner);
    }

    public function get(UuidInterface $uuid): Partner
    {
        return $this->entityManager->find(Partner::class, $uuid->toString());
        ;
    }
}
