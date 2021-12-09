<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Service;

use Pomodoro\Domain\Worker\Entity\Worker;

interface AuthenticationGateway
{
    public function authenticate(string $username, string $password): Worker|bool;

    public function getAuthenticatedWorker(): ?Worker;
}
