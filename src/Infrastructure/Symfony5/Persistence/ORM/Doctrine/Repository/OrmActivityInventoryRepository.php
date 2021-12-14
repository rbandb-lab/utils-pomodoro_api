<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;
use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Worker\Entity\ActivityInventory;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryInterface;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory as OrmActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\UnplannedTask as OrmUnplannedTask;

class OrmActivityInventoryRepository extends ServiceEntityRepository implements ActivityInventoryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrmActivityInventory::class);
    }

    public function get(string $id): ?ActivityInventory
    {
        // TODO: Implement get() method.
    }

    public function save(ActivityInventoryInterface $inventory): void
    {
        // TODO: Implement save() method.
    }


    public function addTodoTaskToWorker(string $workerId, TodoTask $task): void
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = <<<EOF
SELECT todo_task_list_id FROM activity_inventory INNER JOIN orm_worker w on w.activity_inventory_id = activity_inventory.id
WHERE w.id = ?
EOF;
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $workerId);
        $resultSet = $stmt->executeQuery();
        $data = $resultSet->fetchAssociative();
        if ($data !== false && array_key_exists('todo_task_list_id', $data)) {
            $todoListId =  $data['todo_task_list_id'];
            $sql = <<< EOF
INSERT INTO todo_task (id, name, state, task_list_id, category_id) VALUES (?, ?, ?, ?, ?);
EOF;
            try {
                $conn->beginTransaction();
                $conn->executeStatement(
                    $sql,
                    [
                        $task->getId(),
                        $task->getName(),
                        $task->getStatus() ?? "",
                        $todoListId,
                        $task->getCategoryId()
                    ],
                    [
                        ParameterType::STRING,
                        ParameterType::STRING,
                        ParameterType::STRING,
                        ParameterType::STRING,
                        ParameterType::STRING,
                    ]
                );
                $conn->commit();
            } catch (\Exception $exception) {
                $conn->rollBack();
            }

            $conn->close();
        }
    }

    public function addUnplannedTaskToWorker(string $workerId, UnplannedTask $task): void
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = <<<EOF
SELECT unplanned_task_list_id FROM activity_inventory INNER JOIN orm_worker w on w.activity_inventory_id = activity_inventory.id
WHERE w.id = ?
EOF;
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $workerId);
        $resultSet = $stmt->executeQuery();
        $data = $resultSet->fetchAssociative();
        if ($data !== false && array_key_exists('unplanned_task_list_id', $data)) {
            $unplannedTaskListId =  $data['unplanned_task_list_id'];
            $sql = <<< EOF
INSERT INTO unplanned_task (id, name, state, task_list_id, category_id, urgent, deadline) VALUES (?, ?, ?, ?, ?, ?, ?);
EOF;
            try {
                $conn->beginTransaction();
                $conn->executeStatement(
                    $sql,
                    [
                        $task->getId(),
                        $task->getName(),
                        $task->getStatus() ?? "",
                        $unplannedTaskListId,
                        $task->getCategoryId(),
                        $task->isUrgent(),
                        $task->getDeadline()->format('Y-m-d H:i:s')
                    ],
                    [
                        ParameterType::STRING,
                        ParameterType::STRING,
                        ParameterType::STRING,
                        ParameterType::STRING,
                        ParameterType::STRING,
                        ParameterType::BOOLEAN,
                        ParameterType::STRING
                    ]
                );
                $conn->commit();
            } catch (\Exception $exception) {
                $conn->rollBack();
            }

            $conn->close();
        }
    }
}
