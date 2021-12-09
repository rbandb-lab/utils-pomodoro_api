<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Entity;

abstract class AbstractToken
{
    protected string $id;
    protected string $workerId;
    protected string $token;

    public function __construct(string $id, string $workerId, string $token)
    {
        $this->id = $id;
        $this->workerId = $workerId;
        $this->token = $token;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getWorkerId(): string
    {
        return $this->workerId;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
