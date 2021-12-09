<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

use Pomodoro\Domain\Worker\Entity\CycleParameters;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\SharedKernel\Error\Error;

final class UpdateParameters
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function execute(UpdateParametersRequest $request, UpdateParametersPresenter $presenter): void
    {
        $worker = $this->workerRepository->get($request->workerId);
        $response = new UpdateParametersResponse();
        $parameters = $worker->getParameters();

        $pomodoroDuration = $parameters->getPomodoroDuration() !== $request->pomodoroDuration
                ? $request->pomodoroDuration
                : $parameters->getPomodoroDuration();

        $longBreakDuration = $parameters->getLongBreakDuration() !== $request->longBreakDuration
            ? $request->longBreakDuration
            : $parameters->getLongBreakDuration();

        $shortBreakDuration = $parameters->getShortBreakDuration() !== $request->shortBreakDuration
            ? $request->shortBreakDuration
            : $parameters->getShortBreakDuration();

        $startFirstTaskIn = $parameters->getStartFirstTaskIn() !== $request->startFirstTaskIn
            ? $request->startFirstTaskIn
            : $parameters->getStartFirstTaskIn();

        $cycleParameters = new CycleParameters(
            $pomodoroDuration,
            $shortBreakDuration,
            $longBreakDuration,
            $startFirstTaskIn
        );

        $worker->setParameters($cycleParameters);
        $response->workerId = $worker->getId();
        $response->parameters[] = $cycleParameters;

        $presenter->present($response);
    }
}
