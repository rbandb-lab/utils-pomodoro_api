<?php

declare(strict_types=1);

namespace Symfony5\Service\RegistrationlLinkGenerator;

use Pomodoro\SharedKernel\Service\RegistrationLinkGenerator as RegistrationLinkGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationLinkGenerator implements RegistrationLinkGeneratorInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildUrl(string $token): string
    {
        return $this->urlGenerator->generate(
            'validate',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }
}
