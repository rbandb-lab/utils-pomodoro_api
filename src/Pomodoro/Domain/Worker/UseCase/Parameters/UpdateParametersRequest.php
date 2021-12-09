<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

class UpdateParametersRequest
{
    public string $workerId;
    public ?int $pomodoroDuration = null;
    public ?int $longBreakDuration = null;
    public ?int $shortBreakDuration = null;
    public ?int $startFirstTaskIn = null;

    public function withCycleParameters(string $workerId, array $parameters = [])
    {
        $this->workerId = $workerId;
        $this->pomodoroDuration = array_key_exists('pomodoroDuration', $parameters) ? $parameters['pomodoroDuration'] : null;
        $this->longBreakDuration = array_key_exists('longBreakDuration', $parameters) ? $parameters['longBreakDuration'] : null;
        $this->shortBreakDuration = array_key_exists('shortBreakDuration', $parameters) ? $parameters['shortBreakDuration'] : null;
        $this->startFirstTaskIn = array_key_exists('startFirstTaskIn', $parameters) ? $parameters['startFirstTaskIn'] : null;
    }
}
