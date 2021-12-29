<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event\ReadModel;

final class ElasticSearchEventRepository
{
    private const INDEX = 'events';

    protected function index(): string
    {
        return self::INDEX;
    }

    public function store(DomainMessage $message): void
    {
        echo 'handling domain message';
        var_dump($message);
    }
}
