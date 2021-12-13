<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\EventHandler;

use Pomodoro\Domain\Event\EventHandler;
use Pomodoro\Domain\Event\Worker\Async\EmailValidatedEvent;
use Pomodoro\Domain\Worker\Entity\TokenRepository;

final class EmailValidatedEventHandler implements EventHandler
{
    private TokenRepository $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function __invoke(EmailValidatedEvent $event): void
    {
        $payload = $event->getPayload();
        $token = $payload['tokenString'];
        $this->tokenRepository->deleteTokenByString($token);
    }
}
