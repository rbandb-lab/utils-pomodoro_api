<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Entity;

use Pomodoro\Domain\Tracking\Entity\Interruption;
use Pomodoro\Domain\Tracking\Model\Timer\Pomodoro;

final class TodoTask extends Task implements TodoTaskInterface
{
    private ?int $startTask = null;
    private ?int $endTask = null;
    private ?Pomodoro $timer = null;
    private array $pomodoros = [];
    private array $voidTimers = [];
    private array $interruptions = [];

    public function __construct(string $id, string $name, ?string $categoryId = '')
    {
        parent::__construct($id, $name, $categoryId);
    }

    public function isStarted(): bool
    {
        return $this->startTask !== null;
    }

    public function start(\DateTime $now): void
    {
        $this->startTask = $this->startTask ?? $now->getTimestamp();
    }

    public function timerRingsAt(int $endTimeStamp): void
    {
        $this->timer->setEndTs($endTimeStamp);
    }

    public function stopTimer(): void
    {
        $now = new \DateTime();
        $timer = $this->timer;
        $timer->setEndTs($now->getTimestamp());
        $this->voidTimers[] = $timer;
        $this->timer = null;
    }

    public function finish(): void
    {
        // TODO: Implement finish() method.
    }

    public function recordInterruption(Interruption $interruption): void
    {
        if (!in_array($interruption->getId(), $this->interruptions, true)) {
            $this->interruptions[$interruption->getId()] = $interruption;
        }
    }

    public function getInterruptions(): array
    {
        return $this->interruptions;
    }

    /**
     * @param array $interruptions
     */
    public function setInterruptions(array $interruptions): void
    {
        $this->interruptions = $interruptions;
    }

    public function getStartTask(): ?int
    {
        return $this->startTask;
    }

    /**
     * @param int|null $startTask
     */
    public function setStartTask(?int $startTask): void
    {
        $this->startTask = $startTask;
    }

    public function getEndTask(): ?int
    {
        return $this->endTask;
    }

    /**
     * @param int|null $endTask
     */
    public function setEndTask(?int $endTask): void
    {
        $this->endTask = $endTask;
    }

    public function getTimer(): ?Pomodoro
    {
        return $this->timer;
    }

    /**
     * @param Pomodoro|null $timer
     */
    public function setTimer(?Pomodoro $timer): void
    {
        $this->timer = $timer;
    }

    public function getPomodoros(): array
    {
        return $this->pomodoros;
    }

    /**
     * @param array $pomodoros
     */
    public function setPomodoros(array $pomodoros): void
    {
        $this->pomodoros = $pomodoros;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param array $voidTimers
     */
    public function setVoidTimers(array $voidTimers): void
    {
        $this->voidTimers = $voidTimers;
    }
}
