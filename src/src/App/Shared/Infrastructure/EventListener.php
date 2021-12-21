<?php

namespace App\Shared\Infrastructure;


/**
 * Handles dispatched events.
 */
interface EventListener
{
    public function handle(DomainMessage $domainMessage): void;
}
