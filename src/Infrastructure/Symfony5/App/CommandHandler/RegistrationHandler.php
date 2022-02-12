<?php

declare(strict_types=1);

namespace Symfony5\App\CommandHandler;

use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Factory\RegistrationTokenFactory;
use Pomodoro\Domain\Worker\Factory\WorkerFactory;
use Pomodoro\Domain\Worker\UseCase\Register\Register;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterRequest;
use Pomodoro\Presentation\Worker\Register\RegistrationPresenter;
use Pomodoro\SharedKernel\Service\EmailValidator;
use Pomodoro\SharedKernel\Service\IdGenerator;
use Symfony5\Http\UI\Validation\Dto\RegistrationDto;

class RegistrationHandler
{
    private IdGenerator $idGenerator;
    private WorkerFactory $workerFactory;
    private WorkerRepository $workerRepository;
    private EmailValidator $emailValidator;
    private RegistrationTokenFactory $tokenFactory;
    private array $defaultCycleParameters;

    public function __construct(
        IdGenerator $idGenerator,
        WorkerFactory $workerFactory,
        WorkerRepository $workerRepository,
        EmailValidator $emailValidator,
        RegistrationTokenFactory $tokenFactory,
        array $defaultCycleParameters
    ) {
        $this->idGenerator = $idGenerator;
        $this->workerFactory = $workerFactory;
        $this->workerRepository = $workerRepository;
        $this->emailValidator = $emailValidator;
        $this->tokenFactory = $tokenFactory;
        $this->defaultCycleParameters = $defaultCycleParameters;
    }

    public function handle(RegistrationDto $dto, RegistrationPresenter $presenter): RegistrationPresenter
    {
        $request = new RegisterRequest($dto->email);

        $request->withFirstNameAndPassword($dto->firstName, $dto->password);
        $register = new Register(
            $this->idGenerator,
            $this->workerFactory,
            $this->workerRepository,
            $this->emailValidator,
            $this->tokenFactory,
            $this->defaultCycleParameters,
        );

        $register->execute($request, $presenter);
        return $presenter;
    }
}
