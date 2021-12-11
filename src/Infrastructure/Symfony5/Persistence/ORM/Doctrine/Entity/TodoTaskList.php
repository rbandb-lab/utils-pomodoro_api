<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
final class TodoTaskList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\TodoTask",
     *     mappedBy="taskList"
     * )
     * @ORM\JoinColumn(name="todo_tasks_id", referencedColumnName="id")
     */
    private Collection $tasks;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory",
     *     mappedBy="todoTaskList",
     *     cascade={"all"}
     * )
     */
    private ActivityInventory $activityInventory;

    public function __construct(string $id, Collection $tasks, ActivityInventory $activityInventory)
    {
        $this->id = $id;
        $this->tasks = $tasks;
        $this->activityInventory = $activityInventory;
    }
}
