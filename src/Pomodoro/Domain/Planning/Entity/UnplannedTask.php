<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Entity;

final class UnplannedTask extends Task
{
    private ?\DateTime $deadline;
    private bool $urgent = false;

    public function __construct(
        string     $id,
        string     $name,
        bool       $urgent,
        ?string    $categoryId = null,
        ?\DateTime $deadline = null
    ) {
        parent::__construct($id, $name, $categoryId);
        $this->deadline = $deadline;
        $this->urgent = $urgent;
    }

    public function isUrgent(): bool
    {
        return $this->urgent;
    }

    public function getDeadline(): ?\DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTime $dateTime): void
    {
        $this->deadline = $dateTime;
    }

    public function setCategoryId(string $id): void
    {
        $this->categoryId = $id;
    }
}
