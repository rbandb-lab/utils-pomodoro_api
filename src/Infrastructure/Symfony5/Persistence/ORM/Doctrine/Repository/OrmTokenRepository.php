<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pomodoro\Domain\Worker\Entity\TokenRepository;
use Symfony5\Persistence\ORM\Doctrine\Entity\Token;

final class OrmTokenRepository extends ServiceEntityRepository implements TokenRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function deleteTokenByString(string $tokenString)
    {
        $tokenToDelete = $this->findOneBy(['tokenString' => $tokenString]);
        $em = $this->getEntityManager();
        $em->remove($tokenToDelete);
        $em->flush();
    }
}
