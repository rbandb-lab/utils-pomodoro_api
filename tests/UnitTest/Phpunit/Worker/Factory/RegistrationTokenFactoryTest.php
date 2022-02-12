<?php

declare(strict_types=1);

namespace PomodoroTests\UnitTest\Phpunit\Worker\Factory;

use Monolog\Test\TestCase;
use Pomodoro\Domain\Worker\Entity\RegistrationToken;
use Pomodoro\Domain\Worker\Factory\RegistrationTokenFactory;
use PomodoroTests\_Mock\Worker\Factory\RandomStringGenerator;
use Symfony5\Service\IdGenerator\IdGenerator;

class RegistrationTokenFactoryTest extends TestCase
{
    private RegistrationToken $registrationToken;
    private string $tokenValue;

    public function testItGeneratesRandomResult()
    {
        $generator = new RandomStringGenerator();
        $randomString = $generator->getRandom();
        self::assertSame('%DF%F49%DF%E0%83%B3D%0./+4%DC%ECS', $randomString);
        $clean = $generator->stripeSpecialChars($randomString);
        self::assertSame('DFF49DFE083B3D04DCECS', $clean);
        self::assertSame('dff49dfe083b3d04dcecs', $generator->generateRandom());
    }

    public function testItGeneratesToken()
    {
        $generator = new RandomStringGenerator();
        $this->tokenValue = $generator->generateRandom();
        $idGenerator = new IdGenerator();
        $factory = new RegistrationTokenFactory($idGenerator, $generator);
        $this->registrationToken = $factory->createEmailValidationToken('123');
        self::assertNotEmpty($this->registrationToken->getId());
        self::assertSame($this->tokenValue, $this->registrationToken->getToken());
        self::assertSame('123', $this->registrationToken->getWorkerId());
    }
}
