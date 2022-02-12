<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Pomodoro\Domain\Worker\Entity\AbstractToken;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;
use Symfony5\Persistence\ORM\Doctrine\Entity\Token;

final class OrmTokenFactory
{
    public static function toOrm(AbstractToken $token, OrmWorker $ormWorker): Token
    {
        return new Token(
            $token->getId(),
            $ormWorker,
            $token->getToken()
        );
    }
}
