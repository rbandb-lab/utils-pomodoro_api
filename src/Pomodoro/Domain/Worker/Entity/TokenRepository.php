<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Entity;

interface TokenRepository
{
    public function deleteTokenByString(string $tokenString);
}
