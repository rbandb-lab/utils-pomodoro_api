<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Event\Worker\Async;

use Pomodoro\Domain\Event\DomainEvent;
use Pomodoro\Domain\Event\Worker\WorkerEvent;

final class RegistrationSubmitted extends WorkerEvent implements DomainEvent
{
}
