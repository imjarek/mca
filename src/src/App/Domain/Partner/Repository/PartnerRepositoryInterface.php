<?php

declare(strict_types=1);

namespace App\Domain\Partner\Repository;

use App\Domain\Partner\Entity\Partner;
use Ramsey\Uuid\UuidInterface;

interface PartnerRepositoryInterface
{
    public function get(UuidInterface $uuid): Partner;

    public function store(Partner $partner): void;
}
