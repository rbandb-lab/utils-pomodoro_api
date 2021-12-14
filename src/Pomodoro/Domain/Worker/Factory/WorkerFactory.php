<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Factory;

use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterRequest;
use Pomodoro\SharedKernel\Service\IdGenerator;
use Pomodoro\SharedKernel\Service\PasswordHasher;

final class WorkerFactory
{
    private IdGenerator $idGenerator;
    private PasswordHasher $passwordHasher;

    public function __construct(IdGenerator $idGenerator, PasswordHasher $passwordHasher)
    {
        $this->idGenerator = $idGenerator;
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
        $ids = $this->idGenerator->createArrayOfIds(3);

        $inventory = ActivityInventoryFactory::create(
            $id,
            $worker->getId(),
            $ids[0],
            $ids[1],
            $ids[2]
        );
        $worker->attachInventory($inventory);

        return $worker;
    }
}
