<?php

namespace App\Shared\Infrastructure;

use Symfony\Contracts\EventDispatcher\Event;

class DomainMessage extends Event
{
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
