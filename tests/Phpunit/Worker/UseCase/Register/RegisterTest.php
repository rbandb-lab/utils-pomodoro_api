<?php

declare(strict_types=1);

namespace PomodoroTests\Phpunit\Worker\UseCase\Register;

use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Factory\RegistrationTokenFactory;
use Pomodoro\Domain\Worker\Factory\WorkerFactory;
use Pomodoro\Domain\Worker\UseCase\Register\Register;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterPresenter;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterRequest;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterResponse;
use Pomodoro\SharedKernel\Service\EmailValidator;
use PomodoroTests\_Mock\Worker\Entity\InMemoryWorkerRepository;
use PomodoroTests\_Mock\Worker\Factory\RandomStringGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony5\Service\IdGenerator\IdGenerator;
use Symfony5\Service\PasswordHasher\PasswordHasher;

class RegisterTest extends KernelTestCase implements RegisterPresenter
{
    private IdGenerator $idGenerator;
    private WorkerFactory $workerFactory;
    private WorkerRepository $workerRepository;
    private EmailValidator $emailValidator;
    private Register $register;
    private RegisterResponse $response;
    private RegistrationTokenFactory $tokenFactory;
    private MessageBusInterface $eventBus;
    private PasswordHasher $passwordHasher;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $hasher = $container->get('sf_password_hasher');
        $this->idGenerator = new IdGenerator();
        $this->passwordHasher = new PasswordHasher(
            $hasher
        );
        $this->tokenFactory = new RegistrationTokenFactory($this->idGenerator, new RandomStringGenerator());
        $this->workerFactory = new WorkerFactory($this->passwordHasher);
        $this->workerRepository = new InMemoryWorkerRepository();
        $this->emailValidator = new \Symfony5\Validator\EmailValidator();
        $this->eventBus = $container->get('event.bus');
        $this->register = new Register(
            $this->idGenerator,
            $this->workerFactory,
            $this->workerRepository,
            $this->emailValidator,
            $this->tokenFactory,
            [
                'pomodoroDuration' => 1500,
                'shortBreakDuration' => 300,
                'longBreakDuration' => 900,
                'startFirstTaskAfter' => 1500,
            ]
        );
    }

    public function present(RegisterResponse $response): void
    {
        $this->response = $response;
        if (empty($response->errors)) {
            foreach ($response->events as $event) {
                try {
                    $this->eventBus->dispatch($event);
                } catch (\Exception $exception) {
                    echo $exception->getMessage();
                }
            }
        }
    }

    public function testValidateEmailRfc()
    {
        $request = new RegisterRequest('jean*&^+---Loïc%@voilà.com');
        $this->register->execute(
            $request->withFirstNameAndPassword('jean', '12345678'),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertEmpty($this->response->errors);
    }

    public function testItShouldNotValidateEmail()
    {
        $request = new RegisterRequest('jean_example.com');
        $this->register->execute(
            $request->withFirstNameAndPassword('jean', '12345678'),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertNotEmpty($this->response->errors);
        $error = array_shift($this->response->errors);
        self::assertEquals('email:invalid email', (string) $error);
    }

    public function testItShouldNotValidateEmailWithDot()
    {
        $request = new RegisterRequest('Abc.@example.com');
        $this->register->execute(
            $request->withFirstNameAndPassword('jean', '12345678'),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertNotEmpty($this->response->errors);
        $error = array_shift($this->response->errors);
        self::assertEquals('email:invalid email', (string) $error);
    }

    public function testItShouldNotValidateEmailDoubleDot()
    {
        $request = new RegisterRequest('Abc..123@example.com');
        $this->register->execute(
            $request->withFirstNameAndPassword('jean', '12345678'),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertNotEmpty($this->response->errors);
        $error = array_shift($this->response->errors);
        self::assertEquals('email:invalid email', (string) $error);
    }

    public function testItShouldNotValidateEmailChinese()
    {
        $request = new RegisterRequest('我買@屋企.香港');
        $this->register->execute(
            $request->withFirstNameAndPassword('jean', '12345678'),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertEmpty($this->response->errors);
    }

    public function testItShouldValidateEmailStrange()
    {
        $request = new RegisterRequest('user+mailbox/department=shipping@example.com');
        $this->register->execute(
            $request->withFirstNameAndPassword('jean', '12345678'),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertEmpty($this->response->errors);
    }

    public function testItShouldNotValidateTooShortPassword()
    {
        $request = new RegisterRequest('jean@example.com');
        $this->register->execute(
            $request->withFirstNameAndPassword('jean', ''),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertNotEmpty($this->response->errors);
        $error = array_shift($this->response->errors);
        self::assertEquals('password:Value "" is too short, it should have at least 8 characters, but only has 0 characters.', (string) $error);
    }

    public function testItShouldNotValidateTooLongPassword()
    {
        $request = new RegisterRequest('jean@example.com');
        $this->register->execute(
            $request->withFirstNameAndPassword('jean', '1234567890+1234567890+1234567890+1234567890+1234567890+1234567890+'),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertNotEmpty($this->response->errors);
        $error = array_shift($this->response->errors);
        self::assertEquals('password:Value "1234567890+1234567890+1234567890+1234567890+1234567890+1234567890+" is too long, it should have no more than 64 characters, but has 66 characters.', (string) $error);
    }

    public function testItShouldNotValidateTooShortFirstName()
    {
        $request = new RegisterRequest('jean@example.com');
        $this->register->execute(
            $request->withFirstNameAndPassword('', '12345678'),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertNotEmpty($this->response->errors);
        $error = array_shift($this->response->errors);
        self::assertEquals('first_name:Value "" is too short, it should have at least 1 characters, but only has 0 characters.', (string) $error);
    }

    public function testItShouldNotValidateTooLongFirstName()
    {
        $request = new RegisterRequest('jean@example.com');
        $this->register->execute(
            $request->withFirstNameAndPassword('hellolesgensjemappellejeanetmonprenomestbeaucoupbeaucouptroptroplonglonglong', '12345678'),
            $this
        );

        self::assertInstanceOf(RegisterResponse::class, $this->response);
        self::assertNotEmpty($this->response->errors);
        $error = array_shift($this->response->errors);
        self::assertEquals('first_name:Value "hellolesgensjemappellejeanetmonprenomestbeaucoupbeaucouptroptroplonglonglong" is too long, it should have no more than 64 characters, but has 76 characters.', (string) $error);
    }
}
