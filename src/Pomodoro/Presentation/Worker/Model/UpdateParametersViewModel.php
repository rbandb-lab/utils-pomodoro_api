<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Model;

class UpdateParametersViewModel
{
    public ?string $id = null;
    public array $errors = [];
    public array $parameters = [];
}
