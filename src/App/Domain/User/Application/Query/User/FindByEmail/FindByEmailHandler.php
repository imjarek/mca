<?php

declare(strict_types=1);

namespace App\Domain\User\Application\Query\User\FindByEmail;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Shared\Application\Query\Item;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Infrastructure\Persistence\ReadModel\Exception\NotFoundException;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Infrastructure\ReadModel\UserView;
use Doctrine\ORM\NonUniqueResultException;

final class FindByEmailHandler implements QueryHandlerInterface
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function __invoke(FindByEmailQuery $query): Item
    {
        $userView = $this->repository->oneByEmailAsArray($query->email);

        return Item::fromPayload($userView['uuid']->toString(), UserView::TYPE, $userView);
    }
}
