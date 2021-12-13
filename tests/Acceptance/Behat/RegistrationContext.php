<?php

declare(strict_types=1);

namespace PomodoroTests\Acceptance\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Factory\RegistrationTokenFactory;
use Pomodoro\Domain\Worker\Factory\WorkerFactory;
use Pomodoro\Domain\Worker\UseCase\Register\Register;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterPresenter;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterRequest;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterResponse;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailPresenter;
use Pomodoro\SharedKernel\Error\Error;
use Pomodoro\SharedKernel\Service\EmailValidator;
use Pomodoro\SharedKernel\Service\IdGenerator;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotEmpty;

final class RegistrationContext implements Context, RegisterPresenter, ValidateEmailPresenter
{
    private AuthContext $authContext;
    private EmailValidator $emailValidator;
    private HttpClientInterface $httpClient;
    private IdGenerator $idGenerator;
    private MessageBusInterface $eventBus;
    private RegisterResponse $response;
    private RegistrationTokenFactory $tokenFactory;
    private array $defaultCycleParameters;
    private string $mailhogApi;
    private string $mailhogClean;
    private WorkerFactory $workerFactory;
    private WorkerRepository $workerRepository;

    public function __construct(
        EmailValidator $emailValidator,
        IdGenerator $idGenerator,
        RegistrationTokenFactory $tokenFactory,
        HttpClientInterface $httpClient,
        MessageBusInterface $eventBus,
        WorkerFactory $workerFactory,
        WorkerRepository $workerRepository,
        array $defaultCycleParameters,
        string $mailhogApi,
        string $mailhogClean
    ) {
        $this->workerRepository = $workerRepository;
        $this->idGenerator = $idGenerator;
        $this->tokenFactory = $tokenFactory;
        $this->httpClient = $httpClient;
        $this->eventBus = $eventBus;
        $this->workerFactory = $workerFactory;
        $this->emailValidator = $emailValidator;
        $this->defaultCycleParameters = $defaultCycleParameters;
        $this->mailhogApi = $mailhogApi;
        $this->mailhogClean = $mailhogClean;
    }

    /**
     * @BeforeScenario
     */
    public function cleanEmails()
    {
        $this->httpClient->request('DELETE', $this->mailhogClean);
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->authContext = $environment->getContext('PomodoroTests\Acceptance\Behat\AuthContext');
    }

    /**
     * @Given no users exists
     */
    public function noUsersExists()
    {
        $results = $this->workerRepository->getAll();
        if (!empty($results)) {
            foreach ($results as $worker) {
                $this->workerRepository->remove($worker);
            }
        }
        assertEmpty($this->workerRepository->getAll());
    }

    /**
     * @When I register with parameters:
     */
    public function iRegisterWithParameters(TableNode $table)
    {
        $table = $table->getTable();
        $label = array_shift($table);
        $userData = array_pop($table);
        $firstName = $userData[0];
        $email = $userData[1];
        $password = $userData[2];

        $registerRequest = new RegisterRequest($email);
        $registerRequest->pomodoroDuration = array_key_exists(3, $userData) ? (int) $userData[3] : null;
        $registerRequest->longBreakDuration = array_key_exists(4, $userData) ? (int) $userData[4] : null;
        $registerRequest->shortBreakDuration = array_key_exists(5, $userData) ? (int) $userData[5] : null;
        $registerRequest->startFirstTaskAfter = array_key_exists(6, $userData) ? (int) $userData[6] : null;
        $registerCase = new Register(
            $this->idGenerator,
            $this->workerFactory,
            $this->workerRepository,
            $this->emailValidator,
            $this->tokenFactory,
            $this->defaultCycleParameters
        );

        $registerCase->execute(
            $registerRequest->withFirstNameAndPassword($firstName, $password),
            $this
        );
    }

    public function present($response): void
    {
        if (empty($response->errors)) {
            foreach ($response->events as $event) {
                try {
                    $this->eventBus->dispatch($event);
                } catch (\Exception $exception) {
                    echo $exception->getMessage();
                }
            }
        }
        $this->response = $response;
    }

    /**
     * @Then an email is sent to :arg1 with a validation link
     */
    public function anEmailIsSentToWithAValidationLink($arg1)
    {
        $response = $this->httpClient->request('GET', $this->mailhogApi);
        assertEquals(200, $response->getStatusCode());
        $payload = json_decode($response->getContent(), true);
        assertArrayHasKey('items', $payload);
        $mail = array_pop($payload['items'][0]);
        assertEquals($arg1, $mail['To'][0]);
    }

    /**
     * @Then the response payload contains errors
     */
    public function theResponsePayloadContainsErrors()
    {
        assertNotEmpty($this->response->errors);
    }

    /**
     * @Then the response payload should contain no errors
     */
    public function theResponsePayloadShouldContainNoErrors()
    {
        assertEmpty($this->response->errors);
    }

    /**
     * @Then the error message should contain :arg1
     */
    public function theErrorMessageShouldContain($arg1)
    {
        $error = array_shift($this->response->errors);
        assertInstanceOf(Error::class, $error);
        assertEquals($arg1, (string) $error);
    }

    public function viewModel()
    {
        // TODO: Implement viewModel() method.
    }

    public function handleEvents(array $events)
    {
        // TODO: Implement handleEvents() method.
    }
}
