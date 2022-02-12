<?php

declare(strict_types=1);

namespace PomodoroTests\Acceptance\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmail;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailPresenter;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailRequest;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony5\Service\IdGenerator\IdGenerator;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertIsInt;
use function PHPUnit\Framework\assertIsString;

final class EmailContext implements Context, ValidateEmailPresenter
{
    private AuthContext $authContext;
    private HttpClientInterface $httpClient;
    private string $mailhogApi;
    private string $mailhogClean;
    private array $currentMessage = [];
    private string $bodyLink;
    private string $token;
    private $response;
    private WorkerRepository $workerRepository;

    public function __construct(
        HttpClientInterface $httpClient,
        IdGenerator $idGenerator,
        WorkerRepository $workerRepository,
        string $mailhogApi,
        string $mailhogClean,
    ) {
        $this->httpClient = $httpClient;
        $this->idGenerator = $idGenerator;
        $this->workerRepository = $workerRepository;
        $this->mailhogApi = $mailhogApi;
        $this->mailhogClean = $mailhogClean;
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->authContext = $environment->getContext('PomodoroTests\Acceptance\Behat\AuthContext');
    }

    /** @BeforeScenario */
    public function clean()
    {
        $this->httpClient->request('DELETE', $this->mailhogClean);
    }

    /**
     * @Then an email should be sent to :arg1
     */
    public function anEmailShouldBeSentTo($arg1)
    {
        $payload = $this->httpClient->request('GET', $this->mailhogApi);
        $data = json_decode($payload->getContent(), true);
        $message = array_shift($data['items']);
        $this->currentMessage = $message;
        $toArray = array_shift($message['To']);
        assertEquals($toArray['Mailbox'].'@'.$toArray['Domain'], $arg1);
    }

    /**
     * @Then the email title should be :arg1
     */
    public function theEmailTitleShouldBe($arg1)
    {
        assertArrayHasKey('Subject', $this->currentMessage['Content']['Headers']);
        $subject = array_shift($this->currentMessage['Content']['Headers']['Subject']);
        assertEquals($arg1, $subject);
    }

    /**
     * @Then the email content should have a link which contains :arg1
     */
    public function theEmailContentShouldHaveALinkWhichContains($arg1)
    {
        $body = $this->currentMessage['Content']['Body'];
        $urlPosition = strpos($body, $arg1);
        assertIsInt($urlPosition);
        $found = preg_split("/\s/", substr($body, $urlPosition), 1);
        $arguments = explode(' ', $found[0]);
        $this->bodyLink = $arguments[0];
    }

    /**
     * @Then the link should contain a valid token
     */
    public function theLinkShouldContainAValidToken()
    {
        $args = explode('/validate/', $this->bodyLink);
        $token = array_pop($args);
        assertIsString($token);
        assertGreaterThan(0, strlen($token));
        $this->token = $token;
    }

    /**
     * @Given I validate my email with the validation link
     */
    public function iValidateMyEmailWithTheValidationLink()
    {
        $worker = $this->authContext->getAuthenticatedWorker();
        $validationRequest = new ValidateEmailRequest();
        $validationRequest->token = $this->token;
        $validationRequest->workerId = $worker->getId();
        $useCase = new ValidateEmail($this->workerRepository);
        $useCase->execute($validationRequest, $this);
    }

    public function present(ValidateEmailResponse $response): void
    {
        $this->response = $response;
    }

    public function viewModel()
    {
        // TODO: Implement viewModel() method.
    }
}
