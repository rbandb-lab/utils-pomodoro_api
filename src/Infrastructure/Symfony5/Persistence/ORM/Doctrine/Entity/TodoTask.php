<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
final class TodoTask extends Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\TodoTaskList",
     *     inversedBy="tasks"
     * )
     */
    private TodoTaskList $taskList;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $startTask;


    /** @ORM\Embedded(class="Pomodoro") */
    private Pomodoro $timer;


    public function __construct(string $id, string $categoryId, string $name, ?string $state)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->state = $state;
        $this->timer = new Pomodoro();
    }

    public function getStartTask(): ?\DateTimeImmutable
    {
        return $this->startTask;
    }

    public function setStartTask(\DateTimeImmutable $startTask): void
    {
        $this->startTask = $startTask;
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function getTaskList(): TodoTaskList
    {
        return $this->taskList;
    }

    public function setTaskList(TodoTaskList $taskList): void
    {
        $this->taskList = $taskList;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getTimer(): Pomodoro
    {
        return $this->timer;
    }

    public function setTimer(Pomodoro $timer): void
    {
        $this->timer = $timer;
    }
}
