<?php

declare(strict_types=1);

namespace Pomodoro\SharedKernel\Service;

interface MailerService
{
    public function send(
        string $from,
        string $to,
        string $content,
        ?string $subject
    ): void;
}
