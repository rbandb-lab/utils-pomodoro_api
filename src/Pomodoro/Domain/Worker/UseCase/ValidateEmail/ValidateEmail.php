<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\ValidateEmail;

use Pomodoro\Domain\Event\Worker\Async\EmailValidatedEvent;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\SharedKernel\Error\Error;

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

        if (!$worker instanceof Worker) {
            $response->errors[] = new Error('token', 'user-not-found');
            $presenter->present($response);
            return;
        }

        $worker->setEmailValidated(true);
        $response->emailValid = true;
        $response->id = $worker->getId();
        $this->workerRepository->updateWorkerEmailState($worker);
        $response->events[] = new EmailValidatedEvent(
            $worker->getId(),
            EmailValidatedEvent::class,
            [
                'tokenString' => $request->token
            ]
        );


        $presenter->present($response);
    }
}
