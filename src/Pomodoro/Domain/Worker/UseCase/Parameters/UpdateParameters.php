<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Model\CycleParameters;
use Pomodoro\Domain\Worker\Model\CycleParametersValidator;
use Pomodoro\SharedKernel\Error\Error;

final class UpdateParameters
{
    private WorkerRepository $workerRepository;
    private CycleParametersValidator $cycleParametersValidator;

    public function __construct(
        CycleParametersValidator $cycleParametersValidator,
        WorkerRepository $workerRepository
    ) {
        $this->cycleParametersValidator = $cycleParametersValidator;
        $this->workerRepository = $workerRepository;
    }

    public function execute(UpdateParametersRequest $request, UpdateParametersPresenter $presenter): UpdateParametersPresenter
    {
        $worker = $this->workerRepository->get($request->workerId);
        $response = new UpdateParametersResponse();
        $response->workerId = $worker->getId();

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

        $result = $this->cycleParametersValidator->validate($cycleParameters);

        if ($result instanceof Error) {
            $response->errors[$result->fieldName()] = $result->message();
            $presenter->present($response);
            return $presenter;
        }

        $worker->setParameters($cycleParameters);
        $this->workerRepository->updateCycleParametersForWorker($worker);

        $response->parameters[] = $cycleParameters;

        $presenter->present($response);
        return $presenter;
    }
}
