<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;


/**
 * Convenience base class for event sourced aggregate roots.
 */
class EventSourcedAggregateRoot
{

    public function apply($event): void
    {
        $this->handle($event);

    }

    /**
     * Handles event if capable.
     *
     * @param mixed $event
     */
    protected function handle($event): void
    {
        $method = $this->getApplyMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
    }

    /**
     * @param mixed $event
     */
    private function getApplyMethod($event): string
    {
        $classParts = explode('\\', get_class($event));

        return 'apply'.end($classParts);
    }
}
