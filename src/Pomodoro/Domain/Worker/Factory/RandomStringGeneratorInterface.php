<?php

namespace Pomodoro\Domain\Worker\Factory;

interface RandomStringGeneratorInterface
{
    public function generateRandom(): string;

    public function getRandom(): string;

    public function stripeSpecialChars(string $safe): string;
}
