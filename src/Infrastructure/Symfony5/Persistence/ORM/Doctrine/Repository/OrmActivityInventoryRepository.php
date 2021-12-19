<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;
use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Planning\Model\TodoTaskListInterface;
use Pomodoro\Domain\Worker\Entity\ActivityInventory;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryInterface;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory as OrmActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\Pomodoro;
use Symfony5\Persistence\ORM\Doctrine\Entity\TodoTask as OrmTodoTask;
use Symfony5\Persistence\ORM\Doctrine\Factory\OrmTodoTaskFactory;

class OrmActivityInventoryRepository extends ServiceEntityRepository implements ActivityInventoryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrmActivityInventory::class);
    }

    public function get(string $id): ?ActivityInventory
    {
        $qb = $this->createQueryBuilder('inventory');
        $qb->where('inventory.id = :inventoryId')
            ->setParameter('inventoryId', $id);
        return $qb->getQuery()->getSingleResult();
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

    public function getTodoTaskList(string $inventoryId): TodoTaskListInterface
    {
    }

    public function getByWorkerId(string $workerId): ?ActivityInventory
    {
        $qb = $this->createQueryBuilder('inventory');
        $qb
            ->innerJoin('inventory.worker', 'w')
            ->where('w.id = :workerId')
            ->setParameter('workerId', $workerId);

        return $qb->getQuery()->getSingleResult();
    }

    public function getTodoTaskById(string $taskId): ?TodoTask
    {
        $qb = $this->createQueryBuilder('inventory');
        $qb
            ->select('tasks')
            ->from(OrmTodoTask::class, 'tasks')
            ->where('tasks.id = :taskId')
            ->setParameter('taskId', $taskId)
        ;
        return OrmTodoTaskFactory::fromDto($qb->getQuery()->getResult());
    }

    public function saveTodoTask(TodoTask $todoTask): void
    {
        $em = $this->getEntityManager();
        $ormTask = $em->find(OrmTodoTask::class, $todoTask->getId());

        $startedAt = (new \DateTimeImmutable())->setTimestamp($todoTask->getStartTask());
        $ringsAt = (new \DateTimeImmutable())->setTimestamp($todoTask->getTimer()->getEndTs());

        $ormTask->setStartTask($startedAt);

        $timer = new Pomodoro();
        $timer->setStartedAt($startedAt);
        $timer->setRingsAt($ringsAt);

        $ormTask->setTimer($timer);

        $em->persist($ormTask);
        $em->flush();
    }
}
