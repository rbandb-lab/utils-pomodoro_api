<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Register;

use Assert\Assert;
use Assert\LazyAssertionException;
use Pomodoro\Domain\Event\Worker\Async\RegistrationSubmitted;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Factory\RegistrationTokenFactory;
use Pomodoro\Domain\Worker\Factory\WorkerFactory;
use Pomodoro\Domain\Worker\Model\CycleParameters;
use Pomodoro\Domain\Worker\Model\CycleParametersValidator;
use Pomodoro\SharedKernel\Error\Error;
use Pomodoro\SharedKernel\Service\EmailValidator;
use Pomodoro\SharedKernel\Service\IdGenerator;

final class Register
{
    private IdGenerator $idGenerator;
    private WorkerFactory $workerFactory;
    private WorkerRepository $workerRepository;
    private EmailValidator $emailValidator;
    private array $defaultCycleParameters;
    private RegistrationTokenFactory $tokenFactory;

    public function __construct(
        IdGenerator              $idGenerator,
        WorkerFactory            $workerFactory,
        WorkerRepository         $workerRepository,
        EmailValidator           $emailValidator,
        RegistrationTokenFactory $tokenFactory,
        array                    $defaultCycleParameters
    ) {
        $this->idGenerator = $idGenerator;
        $this->workerFactory = $workerFactory;
        $this->workerRepository = $workerRepository;
        $this->emailValidator = $emailValidator;
        $this->tokenFactory = $tokenFactory;
        $this->defaultCycleParameters = $defaultCycleParameters;
    }

    public function execute(RegisterRequest $request, RegisterPresenter $presenter): void
    {
        $request->id = $request->id ?? $this->idGenerator->createId();
        $response = new RegisterResponse();
        $validation = $this->validateRequest($request, $response);
        if (true === $validation) {
            $parameters = $this->createCycle($request);
            if (self::validateCycle($parameters, $response)) {
                $this->saveWorker($request, $response);
            }
        }
        $presenter->present($response);
    }

    private function validateRequest(RegisterRequest $request, RegisterResponse $response): bool
    {
        $email = $request->email;
        $isValid = $this->emailValidator->isValid($email);

        if (!$isValid) {
            $response->errors[] = new Error('email', 'invalid email');

            return false;
        }

        $workerExists = ($this->workerRepository->getByUsername($email)) instanceof Worker;

        if ($workerExists) {
            $response->errors[] = new Error('email', 'already registered');

            return false;
        }

        try {
            Assert::lazy()
                ->that($request->password, 'password')
                ->string('not-a-string')
                ->minLength(8)
                ->maxLength(64)
                ->that($request->firstName, 'first_name')
                ->string('not-a-string')
                ->minLength(1)
                ->maxLength(64)
                ->verifyNow();
        } catch (LazyAssertionException $exception) {
            $exceptions = $exception->getErrorExceptions();
            foreach ($exceptions as $subException) {
                $response->errors[] = new Error($subException->getPropertyPath(), $subException->getMessage());
            }

            return false;
        }

        return true;
    }

    /**
     * @param RegisterRequest $request
     * @return CycleParameters
     */
    public function createCycle(RegisterRequest $request): CycleParameters
    {
        $defaultParams = $this->defaultCycleParameters;
        $request->pomodoroDuration = $request->pomodoroDuration ?? (int)$defaultParams['pomodoroDuration'];
        $request->shortBreakDuration = $request->shortBreakDuration ?? (int)$defaultParams['shortBreakDuration'];
        $request->longBreakDuration = $request->longBreakDuration ?? (int)$defaultParams['longBreakDuration'];
        $request->startFirstTaskAfter = $request->startFirstTaskAfter ?? (int)$defaultParams['startFirstTaskAfter'];

        return new CycleParameters(
            $request->pomodoroDuration,
            $request->shortBreakDuration,
            $request->longBreakDuration,
            $request->startFirstTaskAfter,
        );
    }

    public static function validateCycle(CycleParameters $cycleParameters, RegisterResponse $response): bool
    {
        $validator = new CycleParametersValidator();
        $result = $validator->validate($cycleParameters);

        if ($result !== true) {
            $response->errors[] = $result;
        }

        return true;
    }

    private function saveWorker(RegisterRequest $request, RegisterResponse $response): void
    {
        $request->password = $this->workerFactory->hashPassword($request->password);
        $worker = $this->workerFactory->createFromRequest($request);

        $worker = $this->workerFactory->instanciateInventory($this->idGenerator->createId(), $worker);
        $token = $this->tokenFactory->createEmailValidationToken($worker->getId());
        $worker->addRegistrationToken($token);

        try {
            $this->workerRepository->create($worker);
            $response->workerId = $request->id;
            $response->events[] = new RegistrationSubmitted(
                $worker->getId(),
                RegistrationSubmitted::class,
                [
                    'email' => $worker->getUsername(),
                    'token' => $token->getToken()
                ]
            );
        } catch (\Exception $exception) {
            $response->errors[] = new Error('save-worker', __METHOD__ . ' ' . $exception->getMessage());
        }
    }
}
