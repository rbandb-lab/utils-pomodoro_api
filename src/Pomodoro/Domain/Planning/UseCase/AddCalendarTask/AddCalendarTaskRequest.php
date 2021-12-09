<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddCalendarTask;

class AddCalendarTaskRequest
{
    public string $id = '';
    public string $workerId;
    public string $name;
    public int $startTs;
    public int $endTs;

    public function withWorkerId(string $workerId, string $name, \DateTime $startDt, \DateTime $endDt): self
    {
        $this->workerId = $workerId;
        $this->name = $name;
        $this->startTs = $startDt->getTimestamp();
        $this->endTs = $endDt->getTimestamp();

        return $this;
    }
}
