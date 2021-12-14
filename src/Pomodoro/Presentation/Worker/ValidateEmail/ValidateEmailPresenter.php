<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\ValidateEmail;

use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailPresenter as EmailValidationPresenterInterface;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailResponse;
use Pomodoro\Presentation\Worker\AbstractPresenter;
use Pomodoro\Presentation\Worker\Model\ValidateEmailViewModel;
use Symfony\Component\Messenger\MessageBusInterface;

class ValidateEmailPresenter extends AbstractPresenter implements EmailValidationPresenterInterface
{
    private ?ValidateEmailViewModel $viewModel = null;
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function present(ValidateEmailResponse $response): void
    {
        $this->viewModel = new ValidateEmailViewModel();

        foreach ($response->errors as $error) {
            $this->viewModel->errors[$error->fieldName()] = $error->message();
        }

        $this->viewModel->id = $response->id;
        $this->viewModel->emailValidated = $response->emailValid;
        foreach ($response->events as $event) {
            $this->eventBus->dispatch($event);
        }
    }

    public function viewModel(): ValidateEmailViewModel
    {
        return $this->viewModel;
    }
}
