<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\SharedKernel\Error\Error;

final class ShowParameters
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function execute(ShowParametersRequest $request, ShowParametersPresenter $presenter)
    {
        $response = new ShowParametersResponse();
        $worker = $this->getWorker($request, $response);
        $isValid = $worker instanceof Worker;

        if ($isValid) {
            $response->parameters = $worker->getParameters()->toArray();
        }

        $presenter->present($response);
    }

    private function getWorker(ShowParametersRequest $request, $response): ?Worker
    {
        $worker = $this->workerRepository->get($request->workerId);
        if (!$worker instanceof Worker) {
            $response->errors[] = new Error('id', 'worker not found');
        }

        return $worker;
    }
}
