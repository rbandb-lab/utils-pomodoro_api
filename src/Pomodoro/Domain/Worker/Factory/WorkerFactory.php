<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Factory;

use Pomodoro\Domain\Worker\Model\CycleParameters;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterRequest;
use Pomodoro\SharedKernel\Service\PasswordHasher;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

final class WorkerFactory
{
    private PasswordHasher $passwordHasher;

    public function __construct(PasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashPassword(string $plainTextPassword): string
    {
        return $this->passwordHasher->hash($plainTextPassword);
    }

    public function createFromRequest(RegisterRequest $request): Worker
    {
        return new Worker(
            $request->id,
            $request->email,
            $request->firstName,
            $request->password,
            $request->pomodoroDuration,
            $request->shortBreakDuration,
            $request->longBreakDuration,
            $request->startFirstTaskAfter
        );
    }

    public function instanciateInventory(string $id, Worker $worker): Worker
    {
        $inventory = ActivityInventoryFactory::create($id, $worker->getId());
        $worker->attachInventory($inventory);

        return $worker;
    }
}
