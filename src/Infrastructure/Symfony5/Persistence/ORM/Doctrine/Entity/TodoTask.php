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
}
