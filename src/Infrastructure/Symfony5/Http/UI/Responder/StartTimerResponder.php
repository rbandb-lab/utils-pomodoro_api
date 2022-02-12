<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Responder;

final class StartTimerResponder extends ApiResponder
{
    public function viewModelData($viewModel): array
    {
        return [
            'id' => $viewModel->id,
            'startedAt' => $viewModel->startedAt
        ];
    }
}
