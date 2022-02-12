<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Tracking;

use Pomodoro\Presentation\Worker\Interruption\InterruptionPresenter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony5\App\CommandHandler\InterruptionHandler;
use Symfony5\Http\UI\Responder\InterruptionResponder;
use Symfony5\Http\UI\Validation\Form\InterruptionType;
use Symfony5\Http\UI\Validation\HttpRequestValidator;
use Symfony5\Http\UI\Validation\InputValidationTrait;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

final class InterruptionAction
{
    use InputValidationTrait;

    private HttpRequestValidator $httpRequestValidator;
    private InterruptionHandler $interruptionHandler;
    private InterruptionResponder $responder;
    private FormFactoryInterface $formFactory;

    public function __construct(
        HttpRequestValidator $httpRequestValidator,
        InterruptionHandler $interruptionHandler,
        InterruptionResponder $responder,
        FormFactoryInterface $formFactory
    ) {
        $this->httpRequestValidator = $httpRequestValidator;
        $this->interruptionHandler = $interruptionHandler;
        $this->responder = $responder;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, string $taskId, #[CurrentUser] ?OrmWorker $worker): Response
    {
        $requestValid = $this->httpRequestValidator->checkRequest($request);
        $inputValid = $this->validate($request, InterruptionType::class);
        $presenter = new InterruptionPresenter();

        if ($requestValid && $inputValid) {
            $dto = $this->form->getData();
            $dto->taskId = $taskId;
            $dto->workerId = $worker->getId();
            $presenter = $this->interruptionHandler->handle(
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
