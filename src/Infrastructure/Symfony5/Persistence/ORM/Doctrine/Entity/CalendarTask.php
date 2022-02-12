<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @ORM\Entity
 */
final class CalendarTask extends Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\CalendarTaskList",
     *     inversedBy="tasks"
     * )
     */
    private Collection $taskList;


    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getTaskList(): Collection
    {
        return $this->taskList;
    }

    public function setTaskList(Collection $taskList): void
    {
        $this->taskList = $taskList;
    }
}
