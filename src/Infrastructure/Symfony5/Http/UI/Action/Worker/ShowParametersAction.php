<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Worker;

use Pomodoro\Presentation\Worker\Parameters\ShowParametersPresenter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony5\App\QueryHandler\ShowParametersQueryHandler;
use Symfony5\Http\UI\Responder\ShowParametersResponder;
use Symfony5\Http\UI\Validation\HttpRequestValidator;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

final class ShowParametersAction
{
    private HttpRequestValidator $httpRequestValidator;
    private ShowParametersResponder $responder;
    private ShowParametersQueryHandler $handler;

    public function __construct(
        HttpRequestValidator $httpRequestValidator,
        ShowParametersResponder $showParametersResponder,
        ShowParametersQueryHandler $handler
    ) {
        $this->httpRequestValidator = $httpRequestValidator;
        $this->responder = $showParametersResponder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request, #[CurrentUser] ?OrmWorker $worker): Response
    {
        $this->httpRequestValidator->checkRequest($request);
        $presenter = new ShowParametersPresenter();

        $presenter = $this->handler->handle(
            $worker->getId(),
            $presenter
        );

        return $this->responder->respond(
            $this->httpRequestValidator->getResponseParams(),
            $presenter,
            []
        );
    }
}
