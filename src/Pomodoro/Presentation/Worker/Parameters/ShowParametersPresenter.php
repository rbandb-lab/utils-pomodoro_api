<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Parameters;

use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParametersPresenter as ShowParametersPresenterInterface;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParametersResponse;
use Pomodoro\Presentation\Worker\Model\ShowParametersViewModel;

class ShowParametersPresenter implements ShowParametersPresenterInterface
{
    private ShowParametersViewModel $viewModel;

    public function present(ShowParametersResponse $response): void
    {
        $this->viewModel = new ShowParametersViewModel();
        foreach ($response->errors as $error) {
            $this->viewModel->errors[$error->fieldName()] = $error->message();
        }
        $this->viewModel->parameters = $response->parameters;
    }

    public function viewModel(): ShowParametersViewModel
    {
        return $this->viewModel;
    }
}
