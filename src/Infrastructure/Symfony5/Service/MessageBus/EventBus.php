<?php

declare(strict_types=1);

namespace Symfony5\Service\MessageBus;

use Pomodoro\Domain\Event\DomainEvent;
use Pomodoro\SharedKernel\Service\DomainEventBus;
use Symfony\Component\Messenger\MessageBusInterface;

class EventBus implements DomainEventBus
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function dispatch(DomainEvent $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
