<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Profile;

use Pomodoro\Domain\Worker\Entity\Worker;

final class ShowProfileResponse
{
    public ?Worker $worker = null;
    public array $errors = [];
}
