<?php

namespace Pomodoro\Domain\Worker\Model;

use Pomodoro\SharedKernel\Error\Error;

interface CycleParametersInterface
{
    public function validate(CycleParameters $cycleParameters): bool|Error;
}
