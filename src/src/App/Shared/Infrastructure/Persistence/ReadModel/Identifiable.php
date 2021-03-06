<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) 2020 Broadway project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\ReadModel;

/**
 * Represents a read model.
 */
interface Identifiable
{
    public function getId(): string;
}
