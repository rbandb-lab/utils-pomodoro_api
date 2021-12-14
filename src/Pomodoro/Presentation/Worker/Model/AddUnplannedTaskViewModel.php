<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Model;

final class AddUnplannedTaskViewModel
{
    public array $errors = [];
    public ?string $id = null;
}
