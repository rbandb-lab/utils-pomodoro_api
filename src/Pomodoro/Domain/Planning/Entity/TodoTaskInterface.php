<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Entity;

use Pomodoro\Domain\Tracking\Entity\Interruption;

interface TodoTaskInterface
{
    public function start(): void;

    public function finish(): void;

    public function recordInterruption(Interruption $interruption): void;
}
