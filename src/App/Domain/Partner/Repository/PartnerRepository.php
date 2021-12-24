<?php

declare(strict_types=1);

namespace App\Domain\Partner\Repository;

use App\Domain\Partner\Entity\Partner;
use App\Domain\Partner\Infrastructure\ReadModel\PartnerView;
use App\Domain\Partner\ValueObject\Inn;
use App\Shared\Infrastructure\Persistence\ReadModel\Repository\SqlRepository;
use App\Domain\User\ValueObject\Email;
use App\Shared\Infrastructure\Persistence\ReadModel\Exception\NotFoundException;
use App\Domain\User\Infrastructure\ReadModel\UserView;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

final class PartnerRepository extends SqlRepository implements CheckPartnerByInnInterface
{
    protected function setEntityManager(): void
    {
        /** @var EntityRepository $objectRepository */
        $this->repository = $this->entityManager->getRepository(Partner::class);
    }

    public function store(Partner $partner): void
    {
        $this->add($partner);
    }

    public function get(UuidInterface $uuid): Partner
    {
        /** @var Partner $partner */
        $partner = $this->oneByUuid($uuid->toString());

        return $partner;
    }

    private function getPartnerByInnQueryBuilder(Inn $inn): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('partner')
            ->where('partner.inn = :inn')
            ->setParameter('inn', $inn->toString());
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByUuid(UuidInterface $uuid): PartnerView
    {
        $qb = $this->repository
            ->createQueryBuilder('partner')
            ->where('partner.uuid = :uuid')
            ->setParameter('partner', $uuid->toString());

        return $this->oneOrException($qb);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function existsInn(Inn $inn): ?UuidInterface
    {
        $partner = $this->getPartnerByInnQueryBuilder($inn)
            ->select('partner.uuid')
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        return $partner['uuid'] ?? null;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByInn(Inn $inn): PartnerView
    {
        return $this->oneOrException(
            $this->getPartnerByInnQueryBuilder($inn)
        );
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByEmailAsArray(Email $email): array
    {
        return $this->oneOrException(
            $this->getUserByEmailQueryBuilder($email)
            ->select('
                user.uuid, 
                user.credentials.email, 
                user.createdAt, 
                user.updatedAt'
            ),
            AbstractQuery::HYDRATE_ARRAY
        );
    }

    public function add(User $userModel): void
    {
        $this->register($userModel);
    }
}
