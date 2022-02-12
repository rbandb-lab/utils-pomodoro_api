<?php

declare(strict_types=1);

namespace PomodoroTests\Acceptance\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Pomodoro\Domain\Planning\UseCase\AddCalendarTask\AddCalendarTask;
use Pomodoro\Domain\Planning\UseCase\AddCalendarTask\AddCalendarTaskPresenter;
use Pomodoro\Domain\Planning\UseCase\AddCalendarTask\AddCalendarTaskRequest;
use Pomodoro\Domain\Planning\UseCase\AddTodoTask\AddTodoTask;
use Pomodoro\Domain\Planning\UseCase\AddTodoTask\AddTodoTaskPresenter;
use Pomodoro\Domain\Planning\UseCase\AddTodoTask\AddTodoTaskRequest;
use Pomodoro\Domain\Planning\UseCase\AddUnplannedTask\AddUnplannedTask;
use Pomodoro\Domain\Planning\UseCase\AddUnplannedTask\AddUnplannedTaskPresenter;
use Pomodoro\Domain\Planning\UseCase\AddUnplannedTask\AddUnplannedTaskRequest;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimer;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerPresenter;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerRequest;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerResponse;
use Pomodoro\Domain\Tracking\UseCase\StopTimer\StopTimer;
use Pomodoro\Domain\Tracking\UseCase\StopTimer\StopTimerPresenter;
use Pomodoro\Domain\Tracking\UseCase\StopTimer\StopTimerRequest;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Symfony5\Service\IdGenerator\IdGenerator;
use function PHPUnit\Framework\assertClassHasAttribute;
use function PHPUnit\Framework\assertNotNull;

final class TasksContext implements Context, AddCalendarTaskPresenter, AddTodoTaskPresenter, AddUnplannedTaskPresenter, StartTimerPresenter, StopTimerPresenter
{
    private AuthContext $authContext;
    private WorkerRepository $workerRepository;
    private $response;
    private IdGenerator $idGenerator;
    private ?int $startTs = null;
    private ActivityInventoryRepository $activityInventoryRepository;

    public function __construct(
        IdGenerator                 $idGenerator,
        WorkerRepository            $workerRepository,
        ActivityInventoryRepository $activityInventoryRepository,
    ) {
        $this->idGenerator = $idGenerator;
        $this->workerRepository = $workerRepository;
        $this->activityInventoryRepository = $activityInventoryRepository;
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->authContext = $environment->getContext('PomodoroTests\Acceptance\Behat\AuthContext');
    }

    /**
     * @Given I add a :arg1 task in my :arg2
     */
    public function iAddATaskInMy($arg1, $arg2)
    {
        if ('todoTaskList' === $arg2) {
            $worker = $this->authContext->getAuthenticatedWorker();
            $request = new AddTodoTaskRequest();
            $request->withWorkerId(
                $worker->getId(),
                $worker->getId(),
                $arg1
            );
            $useCase = new AddTodoTask(
                $this->idGenerator,
                $this->activityInventoryRepository
            );
            $useCase->execute($request, $this);
        }
    }

    /**
     * @Given I add a :arg1 task with id :arg2 in my :arg3
     */
    public function iAddATaskWithIdInMy($arg1, $arg2, $arg3)
    {
        if ('todoTaskList' === $arg3) {
            $worker = $this->authContext->getAuthenticatedWorker();
            $request = new AddTodoTaskRequest();
            $request->withWorkerId(
                $arg2,
                $worker->getId(),
                $arg1
            );
            $useCase = new AddTodoTask(
                $this->idGenerator,
                $this->activityInventoryRepository
            );
            $useCase->execute($request, $this);
        }
    }

    /**
     * @Given I start the timer for the :arg1 task
     */
    public function iStartTheTimerForTheTask($arg1)
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new StartTimerRequest();
        $request->withTaskId($worker->getId(), $arg1);
        $useCase = new StartTimer($this->activityInventoryRepository, $this->workerRepository);
        $useCase->execute($request, $this);
        $this->startTs = $this->response->startedAt;
    }

    /**
     * @Given I stop the timer for the :arg1 task
     */
    public function iStopTheTimerForTheTask($arg1)
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new StopTimerRequest();
        $request->withTaskId($worker->getId(), $arg1);
        $useCase = new StopTimer($this->workerRepository);
        $useCase->execute($request, $this);
    }

    /**
     * @Then the TasksContext response payload should contain a :arg1 elements
     */
    public function theTaskscontextResponsePayloadShouldContainAElements($arg1)
    {
        assertNotNull($this->response->id);
    }

    /**
     * @Given I add a :arg1 task in my :arg2 with no deadline
     */
    public function iAddATaskInMyWithNoDeadline($arg1, $arg2)
    {
        if ('unplannedTaskList' === $arg2) {
            $worker = $this->authContext->getAuthenticatedWorker();
            $request = new AddUnplannedTaskRequest();
            $request->withWorkerId(
                $worker->getId(),
                $worker->getId(),
                $arg1,
            );
            $useCase = new AddUnplannedTask(
                $this->activityInventoryRepository
            );
            $useCase->execute($request, $this);
        }
    }

    /**
     * @Given I add a :arg1 task in my calendarTaskList starting at :arg2 and ending at :arg3
     */
    public function iAddATaskInMyCalendartasklistStartingAtAndEndingAt($arg1, $arg2, $arg3)
    {
        $name = $arg1;
        $startDt = $arg2;
        $endDt = $arg3;

        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new AddCalendarTaskRequest();

        $request->withWorkerId(
            $worker->getId(),
            $name,
            new \DateTime($startDt),
            new \DateTime($endDt)
        );

        $useCase = new AddCalendarTask(
            $this->idGenerator,
            $this->workerRepository
        );

        $useCase->execute($request, $this);
    }

    /**
     * @Given I add a :arg1 task in my unplannedTaskList with a deadline to :arg2
     */
    public function iAddATaskInMyUnplannedtasklistWithADeadlineTo($arg1, $arg2)
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new AddUnplannedTaskRequest();
        $request->withWorkerId(
            $this->idGenerator->createId(),
            $worker->getId(),
            $arg1,
            new \DateTime($arg2)
        );
        $useCase = new AddUnplannedTask(
            $this->activityInventoryRepository
        );
        $useCase->execute($request, $this);
    }

    /**
     * @Given the response payload should contain a :arg1 elements I should remember as :arg2
     */
    public function theResponsePayloadShouldContainAElementsIShouldRememberAs($arg1, $arg2)
    {
        assertClassHasAttribute('startedAt', StartTimerResponse::class);
        $this->startTs = $this->response->startedAt;
    }

    public function getStartTs(): ?int
    {
        return $this->startTs;
    }

    public function present($response): void
    {
        $this->response = $response;
    }

    public function viewModel()
    {
        // TODO: Implement viewModel() method.
    }
}
