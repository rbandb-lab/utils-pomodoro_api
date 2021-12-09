<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Profile;

use Assert\Assert;
use Assert\LazyAssertionException;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\SharedKernel\Error\Error;

final class ShowProfile
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function execute(ShowProfileRequest $request, ShowProfilePresenter $presenter): void
    {
        $response = new ShowProfileResponse();
        $isValid = $this->validateRequest($request, $response);
        $worker = $this->getWorker($request, $response);
        $isValid = $isValid && $worker instanceof Worker;

        if ($isValid) {
            $response->worker = $worker;
        }

        $presenter->present($response);
    }

    private function validateRequest(ShowProfileRequest $request, ShowProfileResponse $response): bool
    {
        try {
            Assert::lazy()
                ->that($request->workerId, 'id')
                ->string('not-a-string')
                ->notBlank()
                ->verifyNow();
        } catch (LazyAssertionException $exception) {
            $response->errors[] = new Error($exception->getPropertyPath(), $exception->getMessage());
        }

        return true;
    }

    private function getWorker(ShowProfileRequest $request, $response): ?Worker
    {
        $worker = $this->workerRepository->get($request->workerId);
        if (!$worker instanceof Worker) {
            $response->errors[] = new Error('id', 'worker not found');
        }

        return $worker;
    }
}
