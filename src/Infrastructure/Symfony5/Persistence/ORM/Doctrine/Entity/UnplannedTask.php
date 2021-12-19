<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
final class UnplannedTask extends Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\UnplannedTaskList",
     *     inversedBy="tasks"
     * )
     */
    private UnplannedTaskList $taskList;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $urgent;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $deadline;


    public function __construct(string $id, string $name, string $categoryId, string $state, ?\DateTimeImmutable $deadline = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->categoryId = $categoryId;
        $this->state = $state;
        $this->deadline = $deadline;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTaskList(): UnplannedTaskList
    {
        return $this->taskList;
    }

    public function setTaskList(UnplannedTaskList $taskList): void
    {
        $this->taskList = $taskList;
    }

    public function isUrgent(): bool
    {
        return $this->urgent;
    }

    public function setUrgent(bool $urgent): void
    {
        $this->urgent = $urgent;
    }
    
    public function getDeadline(): ?\DateTimeImmutable
    {
        return $this->deadline;
    }
}
