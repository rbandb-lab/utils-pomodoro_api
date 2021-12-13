<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Tracking;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class InterruptionAction
{
    public function __invoke(): Response
    {
        return new JsonResponse();
    }
}
