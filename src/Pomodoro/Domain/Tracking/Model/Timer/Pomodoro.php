<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\Model\Timer;

final class Pomodoro
{
    private int $startTs;
    private int $endTs;

    public function __construct(\DateTime $startDt)
    {
        $this->startTs = $startDt->getTimestamp();
    }

    public function getStartTs(): int
    {
        return $this->startTs;
    }

    public function getEndTs(): int
    {
        return $this->endTs;
    }

    public function setEndTs(int $endTs): void
    {
        $this->endTs = $endTs;
    }
}
