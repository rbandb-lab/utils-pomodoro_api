<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
final class UnplannedTaskList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\UnplannedTask",
     *     mappedBy="taskList"
     * )
     * @ORM\JoinColumn(name="unplanned_task_id", referencedColumnName="id")
     */
    private Collection $tasks;


    /**
     * @ORM\OneToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory",
     *     mappedBy="unplannedTaskList",
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
