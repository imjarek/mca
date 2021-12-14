<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Shared\Infrastructure\Persistence\ReadModel\Repository\SqlRepository;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class UserStore extends SqlRepository implements UserRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager
    ) {
        parent::__construct($entityManager);
    }

    protected function setEntityManager(): void
    {

    }

    public function store(User $user): void
    {
        $this->register($user);
    }

    public function get(UuidInterface $uuid): User
    {
        /** @var User $user */
        $user = $this->entityManager->find(User::class, $uuid->toString());

        return $user;
    }
}
