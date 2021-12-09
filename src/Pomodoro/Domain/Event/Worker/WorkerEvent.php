<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Event\Worker;

abstract class WorkerEvent
{
    protected string $workerId;
    protected string $eventName;
    protected array $payload;

    public function __construct(string $workerId, string $eventName, array $payload)
    {
        $this->workerId = $workerId;
        $this->eventName = $eventName;
        $this->payload = $payload;
    }

    public function getWorkerId(): string
    {
        return $this->workerId;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
