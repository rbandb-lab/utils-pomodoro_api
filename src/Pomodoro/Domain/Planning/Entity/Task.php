<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Entity;

abstract class Task
{
    public const CREATED = 'CREATED';
    public const RUNNING = 'RUNNING';
    public const PENDING = 'PENDING';
    public const CANCELLED = 'CANCELLED';
    public const POSTPONED = 'POSTPONED';
    public const COMPLETE = 'COMPLETE';

    protected string $id;
    protected ?string $categoryId = null;
    protected string $name;
    protected string $status;

    public function __construct(string $id, string $name, ?string $categoryId = '')
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
