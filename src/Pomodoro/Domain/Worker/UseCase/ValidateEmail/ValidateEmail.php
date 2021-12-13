<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\ValidateEmail;

use Pomodoro\Domain\Worker\Entity\RegistrationToken;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;

final class ValidateEmail
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function execute(ValidateEmailRequest $request, ValidateEmailPresenter $presenter): void
    {
        $worker = $this->workerRepository->findTokenByValue($request->token);
        $response = new ValidateEmailResponse();

        if ($worker instanceof Worker) {
            $worker->setEmailValidated(true);
            $response->emailValid = true;
            $this->workerRepository->save($worker);
        }
        $presenter->present($response);
    }
}
