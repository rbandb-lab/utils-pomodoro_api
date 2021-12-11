<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Register;

use Pomodoro\Domain\Worker\UseCase\Register\RegisterPresenter;
use Pomodoro\Domain\Worker\UseCase\Register\RegisterResponse;
use Pomodoro\Presentation\Worker\AbstractPresenter;
use Pomodoro\Presentation\Worker\Model\RegistrationViewModel;
use Pomodoro\SharedKernel\Service\DomainEventBus;

final class RegistrationPresenter extends AbstractPresenter implements RegisterPresenter
{
    private ?RegistrationViewModel $viewModel = null;

    private DomainEventBus $eventBus;

    public function __construct(DomainEventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function present(RegisterResponse $response): void
    {
        $this->viewModel = new RegistrationViewModel();
        foreach ($response->errors as $error) {
            $this->viewModel->errors[$error->fieldName()] = $error->message();
        }

        $this->viewModel->id = $response->workerId;
        $this->handleEvents($response->events);
    }

    public function viewModel(): RegistrationViewModel
    {
        return $this->viewModel;
    }

    public function handleEvents(array $events)
    {
        foreach ($events as $event) {
            try {
                $this->eventBus->dispatch($event);
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }
}
