<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Infra;

use Pomodoro\Presentation\Worker\Register\RegistrationPresenter;
use Pomodoro\SharedKernel\Service\DomainEventBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony5\App\CommandHandler\RegistrationHandler;
use Symfony5\Http\UI\Responder\RegistrationResponder;
use Symfony5\Http\UI\Validation\Form\RegistrationType;
use Symfony5\Http\UI\Validation\HttpRequestValidator;
use Symfony5\Http\UI\Validation\InputValidationTrait;

final class RegisterAction
{
    use InputValidationTrait;

    private FormFactoryInterface $formFactory;
    private HttpRequestValidator $httpRequestValidator;
    private DomainEventBus $eventBus;
    private RegistrationHandler $registrationHandler;
    private RegistrationResponder $responder;

    public function __construct(
        FormFactoryInterface $formFactory,
        HttpRequestValidator $httpRequestValidator,
        DomainEventBus $eventBus,
        RegistrationHandler $registrationHandler,
        RegistrationResponder $responder
    ) {
        $this->formFactory = $formFactory;
        $this->httpRequestValidator = $httpRequestValidator;
        $this->eventBus = $eventBus;
        $this->registrationHandler = $registrationHandler;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): Response
    {
        $requestValid = $this->httpRequestValidator->checkRequest($request);
        $inputValid = $this->validate($request, RegistrationType::class);
        $presenter = new RegistrationPresenter(
            $this->eventBus
        );

        if ($requestValid && $inputValid) {
            $presenter = $this->registrationHandler->handle(
                $this->form->getData(),
                $presenter
            );
        }

        return $this->responder->respond(
            $this->httpRequestValidator->getResponseParams(),
            $presenter,
            $this->formErrors
        );
    }
}
