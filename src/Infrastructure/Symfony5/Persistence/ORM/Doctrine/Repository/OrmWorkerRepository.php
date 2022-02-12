<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Model\CycleParameters;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;
use Symfony5\Persistence\ORM\Doctrine\Factory\OrmWorkerFactory;

final class OrmWorkerRepository extends ServiceEntityRepository implements WorkerRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrmWorker::class);
    }

    public function add(Worker $worker): void
    {
        // TODO: Implement add() method.
    }

    public function get(string $workerId): ?Worker
    {
        $ormWorker = $this->findWithInventory($workerId);
        if ($ormWorker instanceof OrmWorker) {
            return OrmWorkerFactory::fromOrm($ormWorker);
        }
        return null;
    }

    public function findWithInventory(string $workerId)
    {
        $qb = $this->createQueryBuilder('w');
        $qb
            ->leftJoin('w.activityInventory', 'ai')
            ->where('w.id = :id')
            ->setParameter('id', $workerId);
        return $qb->getQuery()->getSingleResult();
    }

    public function create(Worker $worker): void
    {
        $ormWorker = OrmWorkerFactory::toOrm($worker);
        $em = $this->getEntityManager();
        $em->persist($ormWorker);
        $em->flush();
    }

    public function getByUsername(string $username): ?Worker
    {
        $ormWorker = $this->findOneBy(['username' => $username]);
        if ($ormWorker instanceof OrmWorker) {
            return OrmWorkerFactory::fromOrm($ormWorker);
        }
        return null;
    }

    public function remove(Worker $worker): void
    {
        // TODO: Implement remove() method.
    }

    public function findTokenByValue(string $token): ?Worker
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = <<<EOF
SELECT * FROM orm_worker INNER JOIN token t on orm_worker.id = t.worker_id
WHERE t.token_string = ?
EOF;
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $token);
        $resultSet = $stmt->executeQuery();
        $data = $resultSet->fetchAssociative();
        if ($data !== false && array_key_exists('id', $data)) {
            return OrmWorkerFactory::fromRequestArray($data);
        }
        return null;
    }

    public function updateCycleParametersForWorker(string $workerId, CycleParameters $cycleParameters)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = <<<EOF
UPDATE orm_worker SET pomodoro_duration=?, 
                      short_break_duration=?, 
                      long_break_duration=?,
                      start_first_task_in=?
                      WHERE id=?;
EOF;
        try {
            $conn->beginTransaction();
            $conn->executeStatement(
                $sql,
                [
                    $cycleParameters->getPomodoroDuration(),
                    $cycleParameters->getShortBreakDuration(),
                    $cycleParameters->getLongBreakDuration(),
                    $cycleParameters->getStartFirstTaskIn(),
                    $workerId
                ],
                [
                    ParameterType::INTEGER,
                    ParameterType::INTEGER,
                    ParameterType::INTEGER,
                    ParameterType::INTEGER,
                    ParameterType::STRING
                ]
            );
            $conn->commit();
        } catch (\Exception $exception) {
            $conn->rollBack();
        }

        $conn->close();
    }

    public function updateWorkerEmailState(Worker $worker)
    {
        $em = $this->getEntityManager();
        $ormWorker = $this->find($worker->getId());
        $ormWorker->setEmailValidated($worker->isEmailValidated());
        $em->persist($ormWorker);
        $em->flush();
    }

    public function getWorkerCycleParameters(string $workerId): ?CycleParameters
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM orm_worker WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $workerId);
        $resultSet = $stmt->executeQuery();
        $data = $resultSet->fetchAssociative();
        if ($data !== false && array_key_exists('id', $data)) {
            return new CycleParameters(
                $data['pomodoro_duration'],
                $data['short_break_duration'],
                $data['long_break_duration'],
                $data['start_first_task_in'],
            );
        }
        return null;
    }

    public function getWorkers(): array
    {
        return $this->findAll();
    }
}
