<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\UnplannedTask;

use Pomodoro\Domain\Planning\UseCase\AddUnplannedTask\AddUnplannedTaskPresenter as AddUnplannedTaskPresenterInterface;
use Pomodoro\Domain\Planning\UseCase\AddUnplannedTask\AddUnplannedTaskResponse;
use Pomodoro\Presentation\Worker\Model\AddUnplannedTaskViewModel;

final class AddUnplannedTaskPresenter implements AddUnplannedTaskPresenterInterface
{
    private ?AddUnplannedTaskViewModel $viewModel = null;

    public function present(AddUnplannedTaskResponse $response): void
    {
        $this->viewModel = new AddUnplannedTaskViewModel();
        foreach ($response->errors as $error) {
            $this->viewModel->errors[$error->fieldName()] = $error->message();
        }
        $this->viewModel->id = $response->id;
    }

    public function viewModel(): ?AddUnplannedTaskViewModel
    {
        return $this->viewModel;
    }
}
