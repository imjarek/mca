<?php

declare(strict_types=1);

namespace App\Domain\Partner\Repository;

use App\Domain\Partner\Entity\Partner;
use App\Domain\Partner\Infrastructure\ReadModel\PartnerView;
use App\Domain\Partner\ValueObject\Inn;

interface CheckPartnerByInnInterface
{
    /**
     * @return Partner
     */
    public function oneByInn(Inn $inn): PartnerView;
}