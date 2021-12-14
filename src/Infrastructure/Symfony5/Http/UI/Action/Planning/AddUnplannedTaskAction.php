<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Planning;

use Pomodoro\Presentation\Worker\UnplannedTask\AddUnplannedTaskPresenter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony5\App\CommandHandler\AddUnplannedTaskHandler;
use Symfony5\Http\UI\Responder\AddUnplannedTaskResponder;
use Symfony5\Http\UI\Validation\Form\AddUnplannedTaskType;
use Symfony5\Http\UI\Validation\HttpRequestValidator;
use Symfony5\Http\UI\Validation\InputValidationTrait;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

final class AddUnplannedTaskAction
{
    use InputValidationTrait;

    private HttpRequestValidator $httpRequestValidator;
    private FormFactoryInterface $formFactory;
    private AddUnplannedTaskHandler $addUnplannedTaskHandler;
    private AddUnplannedTaskResponder $responder;

    public function __construct(
        HttpRequestValidator $httpRequestValidator,
        FormFactoryInterface $formFactory,
        AddUnplannedTaskHandler $addUnplannedTaskHandler,
        AddUnplannedTaskResponder $responder
    ) {
        $this->httpRequestValidator = $httpRequestValidator;
        $this->formFactory = $formFactory;
        $this->addUnplannedTaskHandler = $addUnplannedTaskHandler;
        $this->responder = $responder;
    }
    public function __invoke(Request $request, #[CurrentUser] ?OrmWorker $worker): Response
    {
        $requestValid = $this->httpRequestValidator->checkRequest($request);
        $inputValid = $this->validate($request, AddUnplannedTaskType::class);

        $presenter = new AddUnplannedTaskPresenter();

        if ($requestValid && $inputValid) {
            $dto = $this->form->getData();
            $dto->workerId = $worker->getId();

            $presenter = $this->addUnplannedTaskHandler->handle(
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
