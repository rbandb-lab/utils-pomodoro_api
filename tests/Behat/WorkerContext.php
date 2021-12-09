<?php

declare(strict_types=1);

namespace PomodoroTests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParameters;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParametersPresenter;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParametersRequest;
use Pomodoro\Domain\Worker\UseCase\Parameters\UpdateParameters;
use Pomodoro\Domain\Worker\UseCase\Parameters\UpdateParametersPresenter;
use Pomodoro\Domain\Worker\UseCase\Parameters\UpdateParametersRequest;
use Pomodoro\Domain\Worker\UseCase\Profile\ShowProfile;
use Pomodoro\Domain\Worker\UseCase\Profile\ShowProfilePresenter;
use Pomodoro\Domain\Worker\UseCase\Profile\ShowProfileRequest;
use Pomodoro\Domain\Worker\UseCase\Profile\ShowProfileResponse;
use Symfony5\Service\IdGenerator\IdGenerator;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertTrue;

final class WorkerContext implements Context, ShowParametersPresenter, ShowProfilePresenter, UpdateParametersPresenter
{
    private IdGenerator $idGenerator;
    private AuthContext $authContext;
    private WorkerRepository $workerRepository;
    private array $workerParameters = [];
    private $response;

    public function __construct(
        IdGenerator $idGenerator,
        WorkerRepository $workerRepository
    ) {
        $this->idGenerator = $idGenerator;
        $this->workerRepository = $workerRepository;
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->authContext = $environment->getContext('PomodoroTests\Behat\AuthContext');
    }

    /**
     * @Given a worker exists:
     */
    public function aWorkerExists(TableNode $table)
    {
        $table = $table->getTable();
        $labels = array_shift($table);

        foreach ($table as $workerInfo) {
            $id = $workerInfo[0];
            $email = $workerInfo[1];
            $firstName = $workerInfo[2];
            $password = $workerInfo[3];
            $pomodoroDuration = $workerInfo[4];
            $shortBreakDuration = $workerInfo[5];
            $longBreakDuration = $workerInfo[6];
            $startFirstTaskIn = $workerInfo[7];
            $worker = new Worker(
                $id,
                $email,
                $firstName,
                $password,
                $pomodoroDuration,
                $shortBreakDuration,
                $longBreakDuration,
                $startFirstTaskIn
            );
            $this->workerRepository->save($worker);
        }
    }

    /**
     * @Given a user exists:
     */
    public function aUserExists(TableNode $table)
    {
        $table = $table->getTable();
        array_shift($table);
        $table = array_pop($table);

        $id = $this->idGenerator->createId();
        $firstName = $table[0];
        $email = $table[1];
        $password = $table[2];
        $worker = new Worker($id, $email, $firstName, $password);
        $this->workerRepository->add($worker);
    }

    /**
     * @Then I access my profile parameters
     */
    public function iAccessMyProfileParameters()
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new ShowParametersRequest();
        $useCase = new ShowParameters($this->workerRepository);
        $useCase->execute($request->withWorkerId($worker->getId()), $this);
        $this->workerParameters = $this->response->parameters;
    }

    /**
     * @Then the response payload should contain parameters
     */
    public function theResponsePayloadShouldContainParameters()
    {
        assertNotEmpty($this->response->parameters);
    }

    /**
     * @Then the :arg1 node should contains the associative array:
     */
    public function theNodeShouldContainsTheAssociativeArray($arg1, TableNode $table)
    {
        $array = $this->response->{$arg1};
        $table = $table->getTable();
        $labels = array_shift($table);
        $keys = array_map(function ($kv) {
            return $kv[0];
        }, $table);
        $values = array_map(function ($kv) {
            return $kv[1];
        }, $table);

        $inputs = array_combine($keys, $values);

        foreach ($array as $key => $value) {
            assertArrayHasKey($key, $inputs);
            assertEquals($value, $inputs[$key]);
        }
    }

    /**
     * @Then when I access my profile
     */
    public function whenIAccessMyProfile()
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $request = new ShowProfileRequest();
        $response = new ShowProfileResponse();
        $useCase = new ShowProfile($this->workerRepository);
        $useCase->execute($request->withWorkerId($worker->getId()), $this);
        $this->response = $response->worker = $worker;
        assertInstanceOf(Worker::class, $worker);
    }

    /**
     * @Given the Worker property emailValidated should be equal to false
     */
    public function theWorkerPropertyEmailvalidatedShouldBeEqualToFalse()
    {
        assertFalse($this->response->isEmailValidated());
    }

    /**
     * @Then the Worker property emailValidated should be equal to true
     */
    public function theWorkerPropertyEmailvalidatedShouldBeEqualToTrue()
    {
        assertTrue($this->response->isEmailValidated());
    }

    /**
     * @Given I edit my parameters:
     */
    public function iEditMyParameters(TableNode $table)
    {
        $table = $table->getTable();
        $labels = array_shift($table);
        $workerId = $this->authContext->getAuthenticatedWorker()->getId();
        $table = array_pop($table);
        $pomodoroDuration = $table[0];
        $longBreakDuration = $table[1];
        $shortBreakDuration = $table[2];
        $startFirstTaskIn = $table[3];
        $parameters = [
            'pomodoroDuration' => (int) $pomodoroDuration,
            'longBreakDuration' => (int) $longBreakDuration,
            'shortBreakDuration' => (int) $shortBreakDuration,
            'startFirstTaskIn' => (int) $startFirstTaskIn,
        ];
        $request = new UpdateParametersRequest();
        $request->withCycleParameters($workerId, $parameters);
        $useCase = new UpdateParameters($this->workerRepository);
        $useCase->execute($request, $this);
    }

    /**
     * @Then the response payload should contain parameters:
     */
    public function theResponsePayloadShouldContainParameters2(TableNode $table)
    {
    }

    public function present($response): void
    {
        $this->response = $response;
    }
}
