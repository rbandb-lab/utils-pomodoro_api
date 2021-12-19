<?php

declare(strict_types=1);

namespace Symfony5\App\CommandHandler;

use Pomodoro\Domain\Tracking\UseCase\Interruption\InterruptionPresenter;
use Symfony5\Http\UI\Validation\Dto\InterruptionDto;

final class InterruptionHandler
{
    public function handle(InterruptionDto $dto, InterruptionPresenter $presenter): InterruptionPresenter
    {
        dd($dto);
        return $presenter;
    }
}
