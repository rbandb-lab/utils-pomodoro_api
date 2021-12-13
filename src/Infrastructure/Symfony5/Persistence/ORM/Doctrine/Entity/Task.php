<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Task
{
    /**
     * @ORM\Column()
     */
    protected string $categoryId;

    /**
     * @ORM\Column()
     */
    protected string $name;

    /**
     * @ORM\Column()
     */
    protected string $state;
}
