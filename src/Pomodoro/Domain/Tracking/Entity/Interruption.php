<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\Entity;

abstract class Interruption
{
    protected string $id;
    protected string $label;
    protected string $taskId;
    protected \DateTimeImmutable $interruptedDt;


    public function __construct(string $id, string $taskId)
    {
        $this->id = $id;
        $this->interruptedDt = new \DateTimeImmutable();
        $this->taskId = $taskId;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }
}
