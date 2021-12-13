<?php

declare(strict_types=1);

namespace PomodoroTests\Acceptance\Behat;

use Behat\Behat\Context\Context;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Service\AuthenticationGateway;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

final class AuthContext implements Context
{
    private AuthenticationGateway $authenticationGateway;
    private WorkerRepository $workerRepository;

    public function __construct(
        AuthenticationGateway $authenticationGateway,
        WorkerRepository $workerRepository
    ) {
        $this->authenticationGateway = $authenticationGateway;
        $this->workerRepository = $workerRepository;
    }

    /**
     * @Given the worker :arg1 with password :arg2 is authenticated
     */
    public function theWorkerWithPasswordIsAuthenticated($arg1, $arg2)
    {
        assertTrue($this->authenticationGateway->authenticate($arg1, $arg2));
        $authenticatedWorker = $this->authenticationGateway->getAuthenticatedWorker();
        assertEquals($arg1, $authenticatedWorker->getUsername());
    }

    public function getAuthenticatedWorker(): ?Worker
    {
        return $this->authenticationGateway->getAuthenticatedWorker();
    }
}
