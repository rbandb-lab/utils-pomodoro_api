<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pomodoro\Domain\Planning\Model\TodoTaskListInterface;
use Pomodoro\Domain\Planning\Model\UnplannedTaskListInterface;

/**
 * @ORM\Entity()
 */
final class ActivityInventory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker",
     *     mappedBy="activityInventory"
     * )
     */
    private OrmWorker $worker;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\CalendarTaskList",
     *     inversedBy="activityInventory",
     *     cascade={"all"}
     * )
     * @ORM\JoinColumn(name="calendar_task_list_id", referencedColumnName="id")
     */
    private CalendarTaskList $calendarTaskList;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\UnplannedTaskList",
     *     inversedBy="activityInventory",
     *     cascade={"all"}
     * )
     * @ORM\JoinColumn(name="unplanned_task_id", referencedColumnName="id")
     */
    private UnplannedTaskList $unplannedTaskList;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\TodoTaskList",
     *     inversedBy="activityInventory",
     *     cascade={"all"}
     * )
     * @ORM\JoinColumn(name="todo_task_list_id", referencedColumnName="id")
     */
    private TodoTaskList $todoTaskList;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

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

    /**
     * @param OrmWorker $worker
     */
    public function setWorker(OrmWorker $worker): void
    {
        $this->worker = $worker;
    }

    /**
     * @param CalendarTaskList $calendarTaskList
     */
    public function setCalendarTaskList(CalendarTaskList $calendarTaskList): void
    {
        $this->calendarTaskList = $calendarTaskList;
    }

    /**
     * @param UnplannedTaskList $unplannedTaskList
     */
    public function setUnplannedTaskList(UnplannedTaskList $unplannedTaskList): void
    {
        $this->unplannedTaskList = $unplannedTaskList;
    }

    /**
     * @param TodoTaskList $todoTaskList
     */
    public function setTodoTaskList(TodoTaskList $todoTaskList): void
    {
        $this->todoTaskList = $todoTaskList;
    }
}
