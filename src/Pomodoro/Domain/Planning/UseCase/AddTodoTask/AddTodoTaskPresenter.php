<?php

namespace Pomodoro\Domain\Planning\UseCase\AddTodoTask;

use Pomodoro\Presentation\PresenterInterface;

interface AddTodoTaskPresenter extends PresenterInterface
{
    public function present(AddTodoTaskResponse $response): void;
}
