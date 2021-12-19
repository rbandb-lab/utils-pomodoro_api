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
    protected string $name;
    protected ?string $categoryId = null;
    protected ?string $status = null;

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

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}
