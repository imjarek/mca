<?php

declare(strict_types=1);

namespace App\Domain\Partner\Specification;

use App\Domain\Partner\Exception\EmailAlreadyExistException;
use App\Domain\Partner\ValueObject\Inn;

interface UniqueInnSpecificationInterface
{
    /**
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Inn $inn): bool;
}
