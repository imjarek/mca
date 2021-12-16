<?php

declare(strict_types=1);

namespace App\Domain\Partner\Application\Query;

use App\Domain\Partner\ValueObject\Inn;
use App\Shared\Application\Query\QueryInterface;
use Assert\AssertionFailedException;

final class FindByInnQuery implements QueryInterface
{
    public Inn $inn;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $inn)
    {
        $this->inn = Inn::fromString($inn);
    }
}
