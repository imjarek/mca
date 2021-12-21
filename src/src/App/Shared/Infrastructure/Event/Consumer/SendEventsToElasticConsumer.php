<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event\Consumer;

use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use App\Shared\Infrastructure\DomainMessage;
use App\Shared\Infrastructure\Event\ReadModel\ElasticSearchEventRepository;

class SendEventsToElasticConsumer implements MessageSubscriberInterface
{
    private ElasticSearchEventRepository $eventElasticRepository;

    public function __construct(ElasticSearchEventRepository $eventElasticRepository)
    {
        $this->eventElasticRepository = $eventElasticRepository;
    }

    public function __invoke(DomainMessage $event): void
    {
        $this->eventElasticRepository->store($event);
    }

    public static function getHandledMessages(): iterable
    {
        yield DomainMessage::class => [
            'from_transport' => 'events',
            'bus' => 'messenger.bus.event.async',
        ];
        yield DomainMessage::class;
    }
}
