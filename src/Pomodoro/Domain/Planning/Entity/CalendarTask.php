<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Entity;

final class CalendarTask extends Task
{
    private int $startTs;
    private int $endTs;

    public function __construct(string $id, string $name, int $startTs, int $endTs, ?string $categoryId = '')
    {
        parent::__construct($id, $name, $categoryId);
        $this->startTs = $startTs;
        $this->endTs = $endTs;
    }

    public function addInterruption(): void
    {
        // TODO: Implement addInterruption() method.
    }


    public function getStartTs(): int
    {
        return $this->startTs;
    }

    public function getEndTs(): int
    {
        return $this->endTs;
    }
}
