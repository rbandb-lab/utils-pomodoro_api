<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Worker;

use Pomodoro\Presentation\Worker\ValidateEmail\ValidateEmailPresenter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony5\App\CommandHandler\ValidateEmailHandler;
use Symfony5\Http\UI\Responder\ValidateEmailResponder;
use Symfony5\Http\UI\Validation\Dto\ValidateEmailDto;
use Symfony5\Http\UI\Validation\HttpRequestValidator;

final class ValidateEmailAction
{
    private ValidateEmailHandler $validateEmailHandler;
    private ValidateEmailResponder $responder;
    private HttpRequestValidator $httpRequestValidator;
    private MessageBusInterface $eventBus;

    public function __construct(
        ValidateEmailHandler $validateEmailHandler,
        ValidateEmailResponder $responder,
        HttpRequestValidator $httpRequestValidator,
        MessageBusInterface $eventBus
    ) {
        $this->validateEmailHandler = $validateEmailHandler;
        $this->responder = $responder;
        $this->httpRequestValidator = $httpRequestValidator;
        $this->eventBus = $eventBus;
    }

    public function __invoke(Request $request, string $token)
    {
        $requestValid = $this->httpRequestValidator->checkRequest($request);
        $presenter = new ValidateEmailPresenter($this->eventBus);

        $dto = new ValidateEmailDto();
        $dto->token = $token;

        if ($requestValid) {
            $presenter = $this->validateEmailHandler->handle(
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
