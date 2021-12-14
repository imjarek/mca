<?php

namespace App\Shared\Infrastructure\Persistence\ReadModel;

/**
 * Contract for objects serializable by the SimpleInterfaceSerializer.
 */
interface Serializable
{
    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data);

    public function serialize(): array;
}
