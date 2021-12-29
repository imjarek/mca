<?php

declare(strict_types=1);

namespace App\Domain\Order\ValueObject;

class Status
{
    public $name;

    public $displayName;

    public function __construct($name, $displayName)
    {
        $this->name = $name;
        $this->displayName = $displayName;
    }
}
