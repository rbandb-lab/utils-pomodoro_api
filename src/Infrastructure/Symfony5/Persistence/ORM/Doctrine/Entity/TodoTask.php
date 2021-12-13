<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pomodoro\Domain\Planning\Model\TodoTaskListInterface;

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
    private TodoTaskListInterface $taskList;

    public function __construct(string $id, string $categoryId, string $name, ?string $state)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->state = $state;
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

    public function setTaskList(TodoTaskListInterface $taskList): void
    {
        $this->taskList = $taskList;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }
}
