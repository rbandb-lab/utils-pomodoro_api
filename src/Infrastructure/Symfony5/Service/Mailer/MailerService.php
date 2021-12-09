<?php

declare(strict_types=1);

namespace Symfony5\Service\Mailer;

use Pomodoro\SharedKernel\Service\MailerService as MailerServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService implements MailerServiceInterface
{
    private LoggerInterface $logger;
    private MailerInterface $mailer;

    public function __construct(
        LoggerInterface $logger,
        MailerInterface $mailer
    ) {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public function send(string $from, string $to, string $content, ?string $subject): void
    {
        $message = new Email();
        $message->from($from);
        $message->to($to);
        $message->text($content);
        $message->subject($subject);

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            $this->logger->critical(__METHOD__.$e->getMessage());
        }
    }
}
