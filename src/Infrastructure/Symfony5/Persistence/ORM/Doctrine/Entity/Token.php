<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
final class Token
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker",
     *     inversedBy="tokens"
     * )
     * @ORM\JoinColumn(name="worker_id", referencedColumnName="id")
     */
    private OrmWorker $worker;

    /**
     * @ORM\Column(type="string")
     */
    private string $tokenString;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;


    public function __construct(string $id, OrmWorker $worker, string $tokenString)
    {
        $this->id = $id;
        $this->worker = $worker;
        $this->tokenString = $tokenString;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getWorker(): OrmWorker
    {
        return $this->worker;
    }

    public function getTokenString(): string
    {
        return $this->tokenString;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
