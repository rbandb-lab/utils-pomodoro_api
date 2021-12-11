<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Model;

use Assert\Assert;
use Assert\LazyAssertionException;
use Pomodoro\SharedKernel\Error\Error;

class CycleParametersValidator implements CycleParametersInterface
{
    public function validate(CycleParameters $cycleParameters): bool|Error
    {
        try {
            Assert::lazy()
                ->that($cycleParameters->getPomodoroDuration(), 'pomodoroDuration')
                ->integer('property must be an integer')
                ->between(25*60, 50*60, 'between 1500 and 3000')
                ->that($cycleParameters->getShortBreakDuration(), 'shortBreakDuration')
                ->integer('property must be an integer')
                ->between(5*60, 10*60, 'must be between 300 and 600')
                ->that($cycleParameters->getLongBreakDuration(), 'shortBreakDuration')
                ->integer('property must be an integer')
                ->between(15*60, 60*60, 'must be between 900 and 3600')
                ->that($cycleParameters->getLongBreakDuration(), 'longBreakDuration')
                ->greaterOrEqualThan($cycleParameters->getShortBreakDuration())
                ->that($cycleParameters->getShortBreakDuration(), 'shortBreakDuration')
                ->lessOrEqualThan($cycleParameters->getPomodoroDuration(), 'must be inferior to pomodoro_duration')
                ->verifyNow()
            ;
        } catch (LazyAssertionException $exception) {
            $exceptions = $exception->getErrorExceptions();
            foreach ($exceptions as $subException) {
                return new Error($subException->getPropertyPath(), $subException->getMessage());
            }
        }

        return true;
    }
}
