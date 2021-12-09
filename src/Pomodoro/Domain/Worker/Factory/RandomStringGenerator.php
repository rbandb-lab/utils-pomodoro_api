<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Factory;

final class RandomStringGenerator implements RandomStringGeneratorInterface
{
    public function generateRandom(): string
    {
        $random = $this->getRandom();

        return strtolower($this->stripeSpecialChars($random));
    }

    public function getRandom(): string
    {
        return urlencode(random_bytes(12));
    }

    public function stripeSpecialChars(string $safe): string
    {
        return str_replace(['%', '+', '.', '/'], '', $safe);
    }
}
