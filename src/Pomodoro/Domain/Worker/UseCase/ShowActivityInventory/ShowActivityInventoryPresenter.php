<?php

namespace Pomodoro\Domain\Worker\UseCase\ShowActivityInventory;

interface ShowActivityInventoryPresenter
{
    public function present(ShowActivityInventoryResponse $response): void;
}
