<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\ValidateEmail;

use Pomodoro\Domain\Worker\Entity\RegistrationToken;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;

class ValidateEmail
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function execute(ValidateEmailRequest $request, ValidateEmailPresenter $presenter): void
    {
        $token = $this->workerRepository->findTokenByValue($request->workerId, $request->token);
        $response = new ValidateEmailResponse();

        if ($token instanceof RegistrationToken) {
            $worker = $this->workerRepository->get($token->getWorkerId());
            $worker->setEmailValidated(true);
            $response->emailValid = true;
            $this->workerRepository->save($worker);
        }
        $presenter->present($response);
    }
}
