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
        $response = new UpdateParametersResponse();
        $response->workerId = $request->workerId;

        $cycleParameters = new CycleParameters(
            $request->pomodoroDuration,
            $request->shortBreakDuration,
            $request->longBreakDuration,
            $request->startFirstTaskIn
        );

        $result = $this->cycleParametersValidator->validate($cycleParameters);

        if ($result instanceof Error) {
            $response->errors[$result->fieldName()] = $result->message();
            $presenter->present($response);
            return $presenter;
        }

        $this->workerRepository->updateCycleParametersForWorker($request->workerId, $cycleParameters);

        $response->parameters[] = $cycleParameters;

        $presenter->present($response);
        return $presenter;
    }
}
