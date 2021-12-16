<?php

declare(strict_types=1);

namespace App\Domain\Partner\Application\Query;

use App\Domain\Partner\Infrastructure\ReadModel\PartnerView;
use App\Domain\Partner\Repository\PartnerRepository;
use App\Shared\Application\Query\Item;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Infrastructure\Persistence\ReadModel\Exception\NotFoundException;
use Doctrine\ORM\NonUniqueResultException;

final class FindByInnHandler implements QueryHandlerInterface
{
    private UserRepository $repository;

    public function __construct(PartnerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function __invoke(FindByInnQuery $query): Item
    {
        $userView = $this->repository->oneByInn($query->inn);

        return Item::fromPayload($userView['uuid']->toString(), PartnerView::TYPE, $userView);
    }
}
