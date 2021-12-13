<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Symfony5\Persistence\ORM\Doctrine\Factory\OrmWorkerFactory;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

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
        $ormWorker = $this->find($workerId);
        if ($ormWorker instanceof OrmWorker) {
            return OrmWorkerFactory::fromOrm($ormWorker);
        }
        return null;
    }

    public function save(Worker $worker): void
    {
        $ormWorker = OrmWorkerFactory::toOrm($worker);
        $em = $this->getEntityManager();
        $em->persist($ormWorker);
        $em->flush();
    }

    public function getByUsername(string $username): ?Worker
    {
        $ormWorker = $this->findOneBy(['username'=> $username]);
        if ($ormWorker instanceof OrmWorker) {
            return OrmWorkerFactory::fromOrm($ormWorker);
        }
        return null;
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
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
        $data = $resultSet-> fetchAssociative();
        if (array_key_exists('id', $data)) {
            return OrmWorkerFactory::fromRequestArray($data);
        }
        return null;
    }

    public function updateCycleParametersForWorker(Worker $worker)
    {
        $em = $this->getEntityManager();
        $ormWorker = $em->find(OrmWorker::class, $worker->getId());
        $cycleParameters = $worker->getParameters();
        $ormWorker->setParameters($cycleParameters);
        $em->persist($ormWorker);
        $em->flush();
    }
}
