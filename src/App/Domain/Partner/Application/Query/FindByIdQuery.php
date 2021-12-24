<?php

declare(strict_types=1);

namespace App\Domain\Partner\Application\Query;

use App\Shared\Application\Query\QueryInterface;
use App\Domain\User\ValueObject\Email;
use Assert\AssertionFailedException;

final class FindByIdQuery implements QueryInterface
{
    /** @psalm-readonly */
    public Email $email;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
