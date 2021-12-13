<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Action\Planning;

use Pomodoro\Presentation\Worker\TodoTask\AddTodoTaskPresenter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony5\App\CommandHandler\addTodoTaskHandler;
use Symfony5\Http\UI\Responder\AddTodoTaskResponder;
use Symfony5\Http\UI\Validation\Form\AddTodoTaskType;
use Symfony5\Http\UI\Validation\HttpRequestValidator;
use Symfony5\Http\UI\Validation\InputValidationTrait;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

final class AddTodoTaskAction
{
    use InputValidationTrait;

    private HttpRequestValidator $httpRequestValidator;
    private FormFactoryInterface $formFactory;
    private AddTodoTaskHandler $addTodoTaskHandler;
    private AddTodoTaskResponder $responder;

    public function __construct(
        HttpRequestValidator $httpRequestValidator,
        FormFactoryInterface $formFactory,
        AddTodoTaskHandler $addTodoTaskHandler,
        AddTodoTaskResponder $responder
    ) {
        $this->httpRequestValidator = $httpRequestValidator;
        $this->formFactory = $formFactory;
        $this->addTodoTaskHandler = $addTodoTaskHandler;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, #[CurrentUser] ?OrmWorker $worker): Response
    {
        $requestValid = $this->httpRequestValidator->checkRequest($request);
        $inputValid = $this->validate($request, AddTodoTaskType::class);
        $presenter = new AddTodoTaskPresenter();

        $dto = $this->form->getData();
        $dto->workerId = $worker->getId();

        if ($requestValid && $inputValid) {
            $presenter = $this->addTodoTaskHandler->handle(
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
