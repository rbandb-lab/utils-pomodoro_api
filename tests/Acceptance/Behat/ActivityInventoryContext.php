<?php

declare(strict_types=1);

namespace PomodoroTests\Acceptance\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Pomodoro\Domain\Planning\Entity\TodoTaskInterface;
use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Tracking\Model\Timer\Pomodoro;
use Pomodoro\Domain\Tracking\UseCase\Interruption\Interruption;
use Pomodoro\Domain\Tracking\UseCase\Interruption\InterruptionPresenter;
use Pomodoro\Domain\Tracking\UseCase\Interruption\InterruptionRequest;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\UseCase\ShowActivityInventory\ShowActivityInventory;
use Pomodoro\Domain\Worker\UseCase\ShowActivityInventory\ShowActivityInventoryPresenter;
use Pomodoro\Domain\Worker\UseCase\ShowActivityInventory\ShowActivityInventoryRequest;
use Symfony5\Service\IdGenerator\IdGenerator;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

final class ActivityInventoryContext implements Context, ShowActivityInventoryPresenter, InterruptionPresenter
{
    private AuthContext $authContext;
    private TasksContext $tasksContext;
    private \Pomodoro\SharedKernel\Service\IdGenerator $idGenerator;
    private $response;
    private WorkerRepository $workerRepository;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->authContext = $environment->getContext('PomodoroTests\Acceptance\Behat\AuthContext');
        $this->tasksContext = $environment->getContext('PomodoroTests\Acceptance\Behat\TasksContext');
    }

    public function __construct(
        IdGenerator $idGenerator,
        WorkerRepository $workerRepository
    ) {
        $this->idGenerator = $idGenerator;
        $this->workerRepository = $workerRepository;
    }

    /**
     * @Given I access my activity _inventory
     */
    public function iAccessMyActivityInventory()
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new ShowActivityInventoryRequest();
        $request->withWorkerId($worker->getId());
        $useCase = new ShowActivityInventory($this->workerRepository);
        $useCase->execute($request, $this);
    }

    /**
     * @Then the response payload should contain a :arg1 elements
     */
    public function theResponsePayloadShouldContainAElements($arg1)
    {
        assertArrayHasKey($arg1, $this->response->inventory);
    }

    /**
     * @Then the :arg1 node should have :arg2 elements
     */
    public function theNodeShouldHaveElements($arg1, $arg2)
    {
        $inventory = $this->response->inventory;
        assertArrayHasKey('tasks', $inventory[$arg1]);
        assertCount((int)$arg2, $inventory[$arg1]['tasks']);
    }

    /**
     * @Then the first task in the unplannedTaskList should have a :arg1 element with value :arg2
     */
    public function theFirstTaskInTheUnplannedtasklistShouldHaveAElementWithValue($arg1, $arg2)
    {
        $inventory = $this->response->inventory;
        assertArrayHasKey('unplannedTaskList', $inventory);
        assertArrayHasKey('tasks', $inventory['unplannedTaskList']);
        $tasks = $inventory['unplannedTaskList']['tasks'];
        $firstTask = array_shift($tasks);
        $element = $firstTask->getDeadline();
        assertEquals($arg2, $element->getTimestamp());
    }

    /**
     * @Then the first task in the todoTaskList should have one task with a startTask property equal to previous startTs
     */
    public function theFirstTaskInTheTodotasklistShouldHaveOneTaskWithAStarttaskPropertyEqualToPreviousStartts()
    {
        $inventory = $this->response->inventory;
        assertArrayHasKey('tasks', $inventory['todoTaskList']);
        assertNotEmpty($inventory['todoTaskList']['tasks']);
        $task = array_shift($inventory['todoTaskList']['tasks']);
        $start = $task->getStartTask();
        assertEquals($this->tasksContext->getStartTs(), $start);
    }

    /**
     * @Then the first task in the todoTaskList should have no timer and no pomodoro
     */
    public function theFirstTaskInTheTodotasklistShouldHaveNoTimerAndNoPomodoro()
    {
        $inventory = $this->response->inventory;
        assertArrayHasKey('tasks', $inventory['todoTaskList']);
        assertNotEmpty($inventory['todoTaskList']['tasks']);
        $task = array_shift($inventory['todoTaskList']['tasks']);
        assertNull($task->getTimer());
    }


    /**
     * @Then the first task in the todoTaskList should have :arg1 timer and :arg2 pomodoro
     */
    public function theFirstTaskInTheTodotasklistShouldHaveTimerAndPomodoro($arg1, $arg2)
    {
        $inventory = $this->response->inventory;
        assertArrayHasKey('tasks', $inventory['todoTaskList']);
        assertNotEmpty($inventory['todoTaskList']['tasks']);
        $task = array_shift($inventory['todoTaskList']['tasks']);
        assertInstanceOf(TodoTaskInterface::class, $task);
        if ((int)$arg1 === 0) {
            assertNull($task->getTimer());
        } else {
            assertNotNull($task->getTimer());
            assertInstanceOf(Pomodoro::class, $task->getTimer());
        }

        if ((int)$arg2 === 0) {
            assertEmpty($task->getPomodoros());
        } else {
            assertCount((int)$arg1, $task->getPomodoros());
        }
    }

    /**
     * @Given I add an interruption of type :arg1 to the task :arg2 and a :arg3 task in my unplannedTaskList
     */
    public function iAddAnInterruptionOfTypeToTheTaskAndATaskInMy($arg1, $arg2, $arg3)
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new InterruptionRequest();
        $urgent = false;

        $request->taskId = $arg2;
        $request->withWorkerIdAsUnplanned(
            $worker->getId(),
            $urgent,
            $arg1,
            $arg3
        );

        $useCase = new Interruption(
            $this->idGenerator,
            $this->workerRepository
        );

        $useCase->execute($request, $this);
    }

    /**
     * @Given I add an interruption of type :arg1 to the task :arg2 and a :arg3 task :arg4 in my unplannedTaskList
     */
    public function iAddAnInterruptionOfTypeToTheTaskAndATaskInMyUnplannedtasklist($arg1, $arg2, $arg3, $arg4)
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new InterruptionRequest();
        $urgent = $arg4 === 'urgent';

        $request->taskId = $arg2;
        $request->withWorkerIdAsUnplanned(
            $worker->getId(),
            $urgent,
            $arg1,
            $arg3
        );

        $useCase = new Interruption(
            $this->idGenerator,
            $this->workerRepository
        );

        $useCase->execute($request, $this);
    }

    /**
     * @Given I add an interruption of type :arg1 to the task :arg2 and a :arg3 task :arg4 in my unplannedTaskList with a deadline at :arg5
     */
    public function iAddAnInterruptionOfTypeToTheTaskAndATaskInMyUnplannedtasklistWithADeadlineAt($arg1, $arg2, $arg3, $arg4, $arg5)
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new InterruptionRequest();
        $urgent = $arg4 === 'urgent';

        $request->taskId = $arg2;
        $request->deadline = new \DateTime($arg5);
        $request->withWorkerIdAsUnplanned(
            $worker->getId(),
            $urgent,
            $arg1,
            $arg3
        );

        $useCase = new Interruption(
            $this->idGenerator,
            $this->workerRepository
        );

        $useCase->execute($request, $this);
    }


    /**
     * @Then the first task in the todoTaskList should have :arg2 interruption of :arg1 type
     */
    public function theFirstTaskInTheTodotasklistShouldHaveInterruptionOfType($arg1, $arg2)
    {
        $inventory = $this->response->inventory;
        assertArrayHasKey('todoTaskList', $inventory);
        assertArrayHasKey('tasks', $inventory['todoTaskList']);
        $firstTask = array_shift($inventory['todoTaskList']['tasks']);
        $interruptions = $firstTask->getInterruptions();
        assertCount((int) $arg2, $interruptions);
        $interruption = array_shift($interruptions);
        assertInstanceOf(\Pomodoro\Domain\Tracking\Entity\Interruption::class, $interruption);
        if ($arg1 === 'internal') {
            assertInstanceOf(\Pomodoro\Domain\Tracking\Entity\InternalInterruption::class, $interruption);
        } else {
            assertInstanceOf(\Pomodoro\Domain\Tracking\Entity\ExternalInterruption::class, $interruption);
        }
    }

    /**
     * @Then the first task in the unplannedTaskList should have :arg2 task named :arg1 marked as non-urgent with a deadline equal to null
     */
    public function theFirstTaskInTheUnplannedtasklistShouldHaveTaskNamedMarkedAsNonUrgentWithADeadlineEqualToNull($arg1, $arg2)
    {
        $inventory = $this->response->inventory;
        assertArrayHasKey('todoTaskList', $inventory);
        assertArrayHasKey('tasks', $inventory['unplannedTaskList']);
        $unplannedTask = array_shift($inventory['unplannedTaskList']['tasks']);
        assertInstanceOf(UnplannedTask::class, $unplannedTask);
        assertEquals($arg1, $unplannedTask->getName());
        assertFalse($unplannedTask->isUrgent());
        assertNull($unplannedTask->getDeadline());
    }

    /**
     * @Then the first task in the unplannedTaskList should have :arg3 task named :arg1 marked as urgent with a deadline equal to :arg2
     */
    public function theFirstTaskInTheUnplannedtasklistShouldHaveTaskNamedMarkedAsUrgentWithADeadlineEqualTo($arg1, $arg2, $arg3)
    {
        $inventory = $this->response->inventory;
        assertArrayHasKey('todoTaskList', $inventory);
        assertArrayHasKey('tasks', $inventory['unplannedTaskList']);
        assertCount((int) $arg3, $inventory['unplannedTaskList']['tasks']);
        $unplannedTask = array_shift($inventory['unplannedTaskList']['tasks']);
        assertInstanceOf(UnplannedTask::class, $unplannedTask);
        assertEquals($arg1, $unplannedTask->getName());
        assertTrue($unplannedTask->isUrgent());
        assertEquals((int) $arg2, $unplannedTask->getDeadline()?->getTimestamp());
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
