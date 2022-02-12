<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Model;

final class ShowParametersViewModel
{
    public ?string $id = null;
    public array $errors = [];
    public array  $parameters = [];
}
