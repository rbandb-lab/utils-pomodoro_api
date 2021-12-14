<?php

declare(strict_types=1);

namespace Pomodoro\SharedKernel\Service;

interface IdGenerator
{
    public function createId(): string;

    public function createArrayOfIds(int $count): array;
}
