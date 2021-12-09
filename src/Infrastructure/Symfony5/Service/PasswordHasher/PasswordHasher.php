<?php

declare(strict_types=1);

namespace Symfony5\Service\PasswordHasher;

use Pomodoro\SharedKernel\Service\PasswordHasher as DomainPasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class PasswordHasher implements DomainPasswordHasher
{
    private PasswordHasherInterface $sfPasswordHasher;

    public function __construct(PasswordHasherInterface $sfPasswordHasher)
    {
        $this->sfPasswordHasher = $sfPasswordHasher;
    }

    public function hash(string $password): string
    {
        return $this->sfPasswordHasher->hash($password);
    }

    public function verify(string $hash, string $password): bool
    {
        return $this->sfPasswordHasher->verify($hash, $password);
    }
}
