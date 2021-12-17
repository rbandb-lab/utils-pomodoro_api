<?php

declare(strict_types=1);

namespace Symfony5\App\CommandHandler;

use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Model\CycleParametersValidator;
use Pomodoro\Domain\Worker\UseCase\Parameters\UpdateParameters;
use Pomodoro\Domain\Worker\UseCase\Parameters\UpdateParametersPresenter;
use Pomodoro\Domain\Worker\UseCase\Parameters\UpdateParametersRequest;
use Symfony5\Http\UI\Validation\Dto\UpdateParametersDto;

final class UpdateParametersHandler
{
    private CycleParametersValidator $validator;
    private WorkerRepository $workerRepository;

    public function __construct(CycleParametersValidator $validator, WorkerRepository $workerRepository)
    {
        $this->validator = $validator;
        $this->workerRepository = $workerRepository;
    }

    public function handle(UpdateParametersDto $dto, UpdateParametersPresenter $presenter): UpdateParametersPresenter
    {
        $request = new UpdateParametersRequest();
        $request->workerId = $dto->workerId;
        $request->pomodoroDuration = $dto->pomodoroDuration;
        $request->shortBreakDuration = $dto->shortBreakDuration;
        $request->longBreakDuration = $dto->longBreakDuration;
        $request->startFirstTaskIn = $dto->startFirstTaskIn;

        $updateParameters = new UpdateParameters(
            $this->validator,
            $this->workerRepository
        );

        return $updateParameters->execute($request, $presenter);
    }
}
