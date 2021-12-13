<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Parameters;

use Pomodoro\Domain\Worker\UseCase\Parameters\UpdateParametersPresenter as UpdateParametersPresenterInterface;
use Pomodoro\Domain\Worker\UseCase\Parameters\UpdateParametersResponse;
use Pomodoro\Presentation\Worker\Model\UpdateParametersViewModel;

class UpdateParametersPresenter implements UpdateParametersPresenterInterface
{
    public ?UpdateParametersViewModel $viewModel = null;

    public function present(UpdateParametersResponse $response): void
    {
        $this->viewModel = new UpdateParametersViewModel();
        foreach ($response->errors as $fieldName => $message) {
            $this->viewModel->errors[$fieldName] = $message;
        }
        $this->viewModel->id = $response->workerId;
        $this->viewModel->parameters = $response->parameters;
    }

    public function viewModel(): UpdateParametersViewModel
    {
        return $this->viewModel;
    }
}
