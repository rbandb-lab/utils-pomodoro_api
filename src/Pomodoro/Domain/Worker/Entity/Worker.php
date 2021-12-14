<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Entity;

use Pomodoro\Domain\Worker\Model\CycleParameters;

final class Worker
{
    private string $id;
    private string $username;
    private string $firstName;
    private string $password;
    private CycleParameters $parameters;
    private bool $emailValidated;
    private array $tokens;
    private ActivityInventoryInterface $activityInventory;

    public function __construct(
        string $id,
        string $username,
        string $firstName,
        string $password,
        ?int $pomodoroDuration = 1500,
        ?int $shortBreakDuration = 300,
        ?int $longBreakDuration = 300,
        ?int $startFirstTaskIn = 1500
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->password = $password;
        $this->parameters = new CycleParameters(
            $pomodoroDuration,
            $shortBreakDuration,
            $longBreakDuration,
            $startFirstTaskIn
        );
        $this->emailValidated = false;
        $this->tokens = [];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getParameters(): CycleParameters
    {
        return $this->parameters;
    }

    public function isEmailValidated(): bool
    {
        return $this->emailValidated;
    }

    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function attachInventory(ActivityInventoryInterface $activityInventory)
    {
        $this->activityInventory = $activityInventory;
    }

    public function addRegistrationToken(RegistrationToken $token): void
    {
        if (!in_array($token->getId(), $this->tokens, true)) {
            $this->tokens[$token->getId()] = $token;
        }
    }

    public function setEmailValidated(bool $emailValidated): void
    {
        $this->emailValidated = $emailValidated;
    }

    public function getActivityInventory(): ActivityInventoryInterface
    {
        return $this->activityInventory;
    }

    public function setParameters(CycleParameters $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function setActivityInventory(ActivityInventoryInterface $activityInventory): void
    {
        $this->activityInventory = $activityInventory;
    }
}
