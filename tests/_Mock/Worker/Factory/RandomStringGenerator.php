<?php

declare(strict_types=1);

namespace PomodoroTests\_Mock\Worker\Factory;

use Pomodoro\Domain\Worker\Factory\RandomStringGeneratorInterface;

class RandomStringGenerator implements RandomStringGeneratorInterface
{
    public string $result = '%DF%F49%DF%E0%83%B3D%0./+4%DC%ECS';

    public function generateRandom(): string
    {
        $random = $this->getRandom();

        return strtolower($this->stripeSpecialChars($random));
    }

    public function getRandom(): string
    {
        return $this->result;
    }

    public function stripeSpecialChars(string $safe): string
    {
        return str_replace(['%', '+', '.', '/'], '', $safe);
    }
}
