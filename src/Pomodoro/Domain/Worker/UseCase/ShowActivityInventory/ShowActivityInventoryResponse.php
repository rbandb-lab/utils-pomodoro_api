<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\ShowActivityInventory;

class ShowActivityInventoryResponse
{
    /**
     * @param array<string> $items
     */
    public array $inventory = [];
}
