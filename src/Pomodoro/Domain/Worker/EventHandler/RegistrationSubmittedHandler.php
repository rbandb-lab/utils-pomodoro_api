<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\EventHandler;

use Pomodoro\Domain\Event\EventHandler;
use Pomodoro\Domain\Event\Worker\Async\RegistrationSubmitted;
use Pomodoro\SharedKernel\Service\MailerService;
use Pomodoro\SharedKernel\Service\RegistrationLinkGenerator;

class RegistrationSubmittedHandler implements EventHandler
{
    private MailerService $domainMailer;
    private RegistrationLinkGenerator $linkGenerator;

    public function __construct(MailerService $domainMailer, RegistrationLinkGenerator $linkGenerator)
    {
        $this->domainMailer = $domainMailer;
        $this->linkGenerator = $linkGenerator;
    }

    public function __invoke(RegistrationSubmitted $event): void
    {
        $payload = $event->getPayload();
        $token = $payload['token'];
        $this->domainMailer->send(
            'no-reply@example.com',
            $payload['email'],
            'Welcome ! Please validate this link : '.$this->linkGenerator->buildUrl($token).'',
            'Your registration to pomodoro-app'
        );
    }
}
