<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Embeddable() */
class Pomodoro
{
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $startedAt = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $ringsAt = null;

    /**
     * @return \DateTimeImmutable|null
     */
    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTimeImmutable|null $startedAt
     */
    public function setStartedAt(?\DateTimeImmutable $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function getRingsAt(): ?\DateTimeImmutable
    {
        return $this->ringsAt;
    }

    public function setRingsAt(?\DateTimeImmutable $ringsAt): void
    {
        $this->ringsAt = $ringsAt;
    }
}
