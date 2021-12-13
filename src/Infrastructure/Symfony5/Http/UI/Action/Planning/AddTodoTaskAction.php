<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Planning;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AddTodoTaskAction
{
    public function __invoke(): Response
    {
        return new JsonResponse();
    }
}
