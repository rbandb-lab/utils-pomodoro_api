<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\TodoTask;

use Pomodoro\Domain\Planning\UseCase\AddTodoTask\AddTodoTaskPresenter as AddTodoTaskPresenterInterface;
use Pomodoro\Domain\Planning\UseCase\AddTodoTask\AddTodoTaskResponse;
use Pomodoro\Presentation\Worker\Model\AddTodoTaskViewModel;

class AddTodoTaskPresenter implements AddTodoTaskPresenterInterface
{
    private AddTodoTaskViewModel $viewModel;

    public function present(AddTodoTaskResponse $response): void
    {
        $this->viewModel = new AddTodoTaskViewModel();
        foreach ($response->errors as $error) {
            $this->viewModel->errors[$error->fieldName()] = $error->message();
        }
        $this->viewModel->id = $response->id;
    }

    public function viewModel(): AddTodoTaskViewModel
    {
        return $this->viewModel;
    }
}
