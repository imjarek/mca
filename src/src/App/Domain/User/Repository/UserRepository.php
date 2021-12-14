<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Shared\Infrastructure\Persistence\ReadModel\Repository\SqlRepository;
use App\Domain\User\Repository\CheckUserByEmailInterface;
use App\Domain\User\Repository\GetUserCredentialsByEmailInterface;
use App\Domain\User\ValueObject\Email;
use App\Shared\Infrastructure\Persistence\ReadModel\Exception\NotFoundException;
use App\Shared\Infrastructure\Persistence\ReadModel\Repository\MysqlRepository;
use App\Domain\User\Infrastructure\ReadModel\UserView;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

final class UserRepository extends SqlRepository implements CheckUserByEmailInterface, GetUserCredentialsByEmailInterface
{
    protected function setEntityManager(): void
    {
        /** @var EntityRepository $objectRepository */
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function store(User $user): void
    {
        $this->add($user);
    }

    public function get(UuidInterface $uuid): User
    {
        /** @var User $user */
        $user = $this->oneByUuid($uuid->toString());

        return $user;
    }

    private function getUserByEmailQueryBuilder(Email $email): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByUuid(UuidInterface $uuid): UserView
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', $uuid->toString())
        ;

        return $this->oneOrException($qb);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function existsEmail(Email $email): ?UuidInterface
    {
        $userId = $this->getUserByEmailQueryBuilder($email)
            ->select('user.uuid')
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY)
        ;

        return $userId['uuid'] ?? null;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByEmail(Email $email): UserView
    {
        return $this->oneOrException(
            $this->getUserByEmailQueryBuilder($email)
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
