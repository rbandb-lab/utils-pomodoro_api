<?php

namespace Pomodoro\SharedKernel\Service;

interface RegistrationLinkGenerator
{
    public function buildUrl(string $token): string;
}
