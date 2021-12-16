<?php

declare(strict_types=1);

namespace App\Domain\Partner\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use JsonSerializable;

final class Inn implements JsonSerializable
{
    private string $email;

    public function __construct(string $inn)
    {
        $this->inn = $inn;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $inn): self
    {
        Assertion::length($inn, 'Not a valid INN');

        return new self($inn);
    }

    public function toString(): string
    {
        return $this->inn;
    }

    public function __toString(): string
    {
        return $this->inn;
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }
}
