<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony5\Persistence\ORM\Doctrine\Entity\TodoTaskList;

class OrmTodoTaskListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoTaskList::class);
    }
}
