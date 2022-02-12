<?php

namespace Pomodoro\SharedKernel\Service;

use Pomodoro\Domain\Event\DomainEvent;

interface DomainEventBus
{
    public function dispatch(DomainEvent $event): void;
}
