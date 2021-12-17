<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Timer;

use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerPresenter as StartTimerInterface;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerResponse;
use Pomodoro\Presentation\Worker\Model\StartTimerViewModel;

final class StartTimerPresenter implements StartTimerInterface
{
    private StartTimerViewModel $viewModel;

    public function present(StartTimerResponse $response): void
    {
        $this->viewModel = new StartTimerViewModel();
        $this->viewModel->id = $response->id;
        $this->viewModel->errors = $response->errors;
        $this->viewModel->startedAt = $response->startedAt;
    }

    public function viewModel(): StartTimerViewModel
    {
        return $this->viewModel;
    }
}
