<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Interruption;

use Pomodoro\Domain\Tracking\UseCase\Interruption\InterruptionPresenter as InterruptionPresenterInterface;
use Pomodoro\Domain\Tracking\UseCase\Interruption\InterruptionResponse;
use Pomodoro\Presentation\Worker\Model\InterruptionViewModel;

class InterruptionPresenter implements InterruptionPresenterInterface
{
    private InterruptionViewModel $viewModel;

    public function present(InterruptionResponse $response): void
    {
        $this->viewModel = new InterruptionViewModel();
    }

    public function viewModel()
    {
        return $this->viewModel;
    }
}
