<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Tracking;

use Pomodoro\Presentation\Worker\Timer\StartTimerPresenter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony5\App\CommandHandler\StartTimerHandler;
use Symfony5\Http\UI\Responder\StartTimerResponder;
use Symfony5\Http\UI\Validation\Dto\StartTimerDto;
use Symfony5\Http\UI\Validation\HttpRequestValidator;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

final class StartTimerAction
{
    private HttpRequestValidator $httpRequestValidator;
    private StartTimerHandler $startTimerHandler;
    private StartTimerResponder $responder;

    public function __construct(
        HttpRequestValidator $httpRequestValidator,
        StartTimerHandler    $startTimerHandler,
        StartTimerResponder  $responder
    ) {
        $this->httpRequestValidator = $httpRequestValidator;
        $this->startTimerHandler = $startTimerHandler;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, #[CurrentUser] ?OrmWorker $worker, string $taskId): Response
    {
        $requestValid = $this->httpRequestValidator->checkRequest($request);
        $presenter = new StartTimerPresenter();

        if ($requestValid) {
            $dto = new StartTimerDto();
            $dto->taskId = $taskId;
            $dto->workerId = $worker->getId();
            $presenter = $this->startTimerHandler->handle(
                $dto,
                $presenter
            );
        }

        return $this->responder->respond(
            $this->httpRequestValidator->getResponseParams(),
            $presenter,
            []
        );
    }
}
