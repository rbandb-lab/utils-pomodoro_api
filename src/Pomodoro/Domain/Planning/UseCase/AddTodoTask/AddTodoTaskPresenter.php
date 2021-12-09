<?php

namespace Pomodoro\Domain\Planning\UseCase\AddTodoTask;

interface AddTodoTaskPresenter
{
    public function present(AddTodoTaskResponse $response): void;
}
