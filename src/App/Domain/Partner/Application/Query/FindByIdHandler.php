<?php

declare(strict_types=1);

namespace App\Domain\Partner\Application\Query;

use App\Shared\Application\Query\Item;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Infrastructure\Persistence\ReadModel\Exception\NotFoundException;
use App\Domain\Partner\Repository\PartnerRepository;
use App\Domain\Partner\Infrastructure\ReadModel\PartnerView;
use Doctrine\ORM\NonUniqueResultException;

final class FindByIdHandler implements QueryHandlerInterface
{
    private PartnerRepository $repository;

    public function __construct(PartnerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function __invoke(FindByIdQuery $query): Item
    {
        $partnerView = $this->repository->oneByUuid($query->uuid);

        return Item::fromPayload($partnerView['uuid']->toString(), PartnerView::TYPE, $partnerView);
    }
}
