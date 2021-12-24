<?php

declare(strict_types=1);

namespace App\Domain\Partner\Exception;

class InnAlreadyExistException extends \InvalidArgumentException implements \Throwable
{
    public function __construct()
    {
        parent::__construct('Inn already registered.');
    }
}
