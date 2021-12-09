<?php

declare(strict_types=1);

namespace PomodoroTests\_Mock\Worker\Service;

use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Service\AuthenticationGateway as AuthenticationGatewayInterface;

class AuthenticationGateway implements AuthenticationGatewayInterface
{
    private WorkerRepository $workerRepository;
    private ?Worker $authenticatedWorker = null;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function authenticate(string $username, string $password): Worker|bool
    {
        $worker = $this->workerRepository->getByUsername($username);
        if (!$worker) {
            return false;
        }
        if ($this->checkCredentials($worker, $password)) {
            $this->authenticatedWorker = $worker;

            return true;
        }

        return false;
    }

    public function checkCredentials(?Worker $worker, string $password): bool
    {
        if ($worker) {
            return $worker->getUsername() === trim($worker->getUsername()) && ($worker->getPassword() === trim($password) || password_verify($password, $worker->getPassword()));
        }

        return false;
    }

    public function getAuthenticatedWorker(): ?Worker
    {
        return $this->authenticatedWorker;
    }
}
