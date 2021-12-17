<?php

declare(strict_types=1);

namespace PomodoroTests\UnitTest\Phpunit\Tracking\UseCase\StartTimer;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Tracking\Model\Timer\Pomodoro;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimer;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerPresenter;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerRequest;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerResponse;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Factory\WorkerFactory;
use Pomodoro\Presentation\Worker\Model\StartTimerViewModel;
use Pomodoro\SharedKernel\Error\Error;
use PomodoroTests\_Mock\Worker\Entity\InMemoryActivityInventoryRepository;
use PomodoroTests\_Mock\Worker\Entity\InMemoryWorkerRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony5\Service\IdGenerator\IdGenerator;
use Symfony5\Service\PasswordHasher\PasswordHasher;

class StartTimerTest extends KernelTestCase implements StartTimerPresenter
{
    private Worker $worker;
    private WorkerFactory $workerFactory;
    private WorkerRepository $workerRepository;
    private ActivityInventoryRepository $inventoryRepository;
    private \Pomodoro\SharedKernel\Service\IdGenerator $idGenerator;
    private StartTimerViewModel $viewModel;

    public function testTaskIsStarted()
    {
        $cycleParameters = $this->worker->getParameters();
        $request = new StartTimerRequest();
        $request->withTaskId($this->worker->getId(), 'foo');

        $startTimer = new StartTimer($this->inventoryRepository, $this->workerRepository);
        $startTimer->execute($request, $this);

        $task = $this->inventoryRepository->getTodoTaskById('foo');
        self::assertTrue($task->isStarted());
        self::assertInstanceOf(Pomodoro::class, $task->getTimer());

        $now = new \DateTime();
        self::assertGreaterThanOrEqual($task->getTimer()->getStartTs(), $now->getTimestamp());
        self::assertEquals(
            $task->getTimer()->getStartTs() + $cycleParameters->getPomodoroDuration(),
            $task->getTimer()->getEndTs()
        );
    }

    public function testCannotStartTwice()
    {
        $request = new StartTimerRequest();
        $request->withTaskId($this->worker->getId(), 'foo');

        $startTimer = new StartTimer($this->inventoryRepository, $this->workerRepository);
        $startTimer->execute($request, $this);
        $startTimer->execute($request, $this);
        self::assertNotEmpty($this->viewModel->errors);
        $error = array_shift($this->viewModel->errors);
        self::assertInstanceOf(Error::class, $error);
        self::assertEquals('timer', $error->fieldName());
        self::assertEquals('already-started', $error->message());
    }

    public function viewModel()
    {
        return $this->viewModel;
    }

    public function present(StartTimerResponse $response): void
    {
        $this->viewModel = new StartTimerViewModel();
        $this->viewModel->id = $response->id;
        $this->viewModel->errors = $response->errors;
        $this->viewModel->startedAt = $response->startedAt;
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->idGenerator = new IdGenerator();
        $sfPasswordHasher = $container->get('sf_password_hasher');

        $this->workerFactory = new WorkerFactory(
            $this->idGenerator,
            new PasswordHasher($sfPasswordHasher)
        );

        $this->worker = new Worker(
            '123',
            'toto@example.com',
            'toto',
            '12345678',
            1500,
            300,
            900,
            1500
        );

        $worker = $this->workerFactory->instanciateInventory($this->idGenerator->createId(), $this->worker);

        $this->workerRepository = new InMemoryWorkerRepository();
        $this->workerRepository->save($worker);
        $this->inventoryRepository = new InMemoryActivityInventoryRepository(
            $this->workerRepository
        );

        $this->inventoryRepository->addTodoTaskToWorker(
            $worker->getId(),
            new TodoTask('foo', 'foo')
        );
        $this->inventoryRepository->addTodoTaskToWorker(
            $worker->getId(),
            new TodoTask('bar', 'bar')
        );
        $this->worker = $worker;
    }
}
