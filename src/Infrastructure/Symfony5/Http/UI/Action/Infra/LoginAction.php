<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Infra;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

final class LoginAction
{
    public function __invoke(Request $request, #[CurrentUser] ?OrmWorker $worker): Response
    {
        $data = [
            'message' => 'missing credentials',
        ];
        $status = Response::HTTP_UNAUTHORIZED;

        if ($worker !== null) {
            $data = [
                'user' => $worker->getFirstName()
            ];
            $status = Response::HTTP_OK;
        }

        return new JsonResponse($data, $status);
    }
}
