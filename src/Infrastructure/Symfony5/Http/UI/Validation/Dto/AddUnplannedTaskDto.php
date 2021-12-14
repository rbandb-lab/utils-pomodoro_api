<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation\Dto;

class AddUnplannedTaskDto
{
    public string $workerId;
    public string $taskName;
    public bool $urgent;
    public \DateTime $deadline;
}
