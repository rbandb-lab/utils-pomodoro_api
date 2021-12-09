<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Factory;

use Pomodoro\Domain\Worker\Entity\ActivityInventory;

final class ActivityInventoryFactory
{
    public static function create(string $id, string $workerId): ActivityInventory
    {
        return new ActivityInventory(
            id: $id,
            workerId: $workerId
        );
    }
}
