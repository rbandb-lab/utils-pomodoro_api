<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Worker;

use Pomodoro\Presentation\Worker\Parameters\UpdateParametersPresenter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony5\App\CommandHandler\UpdateParametersHandler;
use Symfony5\Http\UI\Responder\UpdateParametersResponder;
use Symfony5\Http\UI\Validation\Form\UpdateParametersType;
use Symfony5\Http\UI\Validation\HttpRequestValidator;
use Symfony5\Http\UI\Validation\InputValidationTrait;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

class UpdateParametersAction
{
    use InputValidationTrait;

    private FormFactoryInterface $formFactory;
    private HttpRequestValidator $httpRequestValidator;
    private UpdateParametersHandler $updateParametersHandler;
    private UpdateParametersResponder $responder;

    public function __construct(
        FormFactoryInterface $formFactory,
        HttpRequestValidator $httpRequestValidator,
        UpdateParametersHandler $updateParametersHandler,
        UpdateParametersResponder $responder
    ) {
        $this->formFactory = $formFactory;
        $this->httpRequestValidator = $httpRequestValidator;
        $this->updateParametersHandler = $updateParametersHandler;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, #[CurrentUser] ?OrmWorker $worker): Response
    {
        $requestValid = $this->httpRequestValidator->checkRequest($request);
        $inputValid = $this->validate($request, UpdateParametersType::class);
        $presenter = new UpdateParametersPresenter();
        $dto = $this->form->getData();
        $dto->workerId = $worker->getId();

        if ($requestValid && $inputValid) {
            $presenter = $this->updateParametersHandler->handle(
                $dto,
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
