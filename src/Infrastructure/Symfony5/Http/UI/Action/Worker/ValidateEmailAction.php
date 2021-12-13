<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Worker;

use Pomodoro\Presentation\Worker\ValidateEmail\ValidateEmailPresenter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony5\App\CommandHandler\ValidateEmailHandler;
use Symfony5\Http\UI\Responder\ValidateEmailResponder;
use Symfony5\Http\UI\Validation\Dto\ValidateEmailDto;
use Symfony5\Http\UI\Validation\Form\EmailValidationType;
use Symfony5\Http\UI\Validation\HttpRequestValidator;
use Symfony5\Http\UI\Validation\InputValidationTrait;

final class ValidateEmailAction
{
    private FormFactoryInterface $formFactory;
    private ValidateEmailHandler $validateEmailHandler;
    private ValidateEmailResponder $responder;
    private HttpRequestValidator $httpRequestValidator;

    public function __construct(
        FormFactoryInterface $formFactory,
        ValidateEmailHandler $validateEmailHandler,
        ValidateEmailResponder $responder,
        HttpRequestValidator $httpRequestValidator
    ) {
        $this->formFactory = $formFactory;
        $this->validateEmailHandler = $validateEmailHandler;
        $this->responder = $responder;
        $this->httpRequestValidator = $httpRequestValidator;
    }

    public function __invoke(Request $request, string $token)
    {
        $requestValid = $this->httpRequestValidator->checkRequest($request);
        $presenter = new ValidateEmailPresenter();

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
