<?php

declare(strict_types=1);

namespace PomodoroTests\UnitTest\Phpunit\Worker\UseCase;

use PHPUnit\Framework\TestCase;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use PomodoroTests\_Mock\Worker\Entity\InMemoryWorkerRepository;

class WorkerTest extends TestCase
{
    protected WorkerRepository $workerRepository;

    protected function setUp(): void
    {
        $this->workerRepository = new InMemoryWorkerRepository();
        $worker = new Worker(
            '123',
            'toto@example.com',
            'toto',
            '12345678',
            1500,
            300,
            900,
            1500
        );
        $this->workerRepository->add($worker);
    }

    public function testWorkerExists()
    {
        $worker = $this->workerRepository->get('123');
        self::assertInstanceOf(Worker::class, $worker);
    }
}
