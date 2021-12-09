<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Entity;

final class Category
{
    private string $id;
    private string $name;
    private ?Category $parent;
    private array $children = [];

    public function __construct(string $id, string $name, ?Category $parent = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parent = $parent;
        $this->children = [];
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParent(): ?Category
    {
        return $this->parent;
    }

    public function getChildren(): array
    {
        return $this->children;
    }
}
