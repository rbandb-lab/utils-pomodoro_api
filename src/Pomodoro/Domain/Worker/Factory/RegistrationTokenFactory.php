<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Factory;

use Pomodoro\Domain\Worker\Entity\RegistrationToken;
use Pomodoro\SharedKernel\Service\IdGenerator;

final class RegistrationTokenFactory extends AbstractTokenFactory
{
    private IdGenerator $idGenerator;
    private RandomStringGeneratorInterface $randomStringGenerator;

    public function __construct(IdGenerator $idGenerator, RandomStringGeneratorInterface $randomStringGenerator)
    {
        $this->idGenerator = $idGenerator;
        $this->randomStringGenerator = $randomStringGenerator;
    }

    public function createEmailValidationToken(string $workerId): RegistrationToken
    {
        $value = $this->randomStringGenerator->generateRandom();

        return new RegistrationToken(
            $this->idGenerator->createId(),
            $workerId,
            $value
        );
    }
}
