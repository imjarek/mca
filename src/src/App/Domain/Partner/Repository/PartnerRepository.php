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

    public function get(UuidInterface $uuid): User
    {
        /** @var User $partner */
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
    public function oneByUuid(UuidInterface $uuid): UserView
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
        $userId = $this->getPartnerByInnQueryBuilder($inn)
            ->select('partner.uuid')
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        return $userId['uuid'] ?? null;
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

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     *
     * @return array{0: \Ramsey\Uuid\UuidInterface, 1: Email, 2: \App\User\Domain\ValueObject\Auth\HashedPassword}
     */
    public function getCredentialsByEmail(Email $email): array
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());

        $user = $this->oneOrException($qb, AbstractQuery::HYDRATE_ARRAY);

        return [
            $user['uuid'],
            $user['credentials.email'],
            $user['credentials.hashedPassword'],
        ];
    }
}
