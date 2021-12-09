<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Service;

use Pomodoro\Domain\Worker\Entity\ActivityInventoryInterface;

abstract class AbstractScheduler implements SchedulerInterface
{
    private ActivityInventoryInterface $activityInventory;

    public function __construct(ActivityInventoryInterface $activityInventory)
    {
        $this->activityInventory = $activityInventory;
    }

    public function buildSchedule(): void
    {
    }

    public function currentTask()
    {
    }

    public function getActivityInventory(): ActivityInventoryInterface
    {
        return $this->activityInventory;
    }
}
