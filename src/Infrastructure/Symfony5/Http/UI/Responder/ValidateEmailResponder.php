<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Responder;

final class ValidateEmailResponder extends ApiResponder
{
    public function viewModelData($viewModel): array
    {
        return [
            'id' => $viewModel->id,
            'emailValidated' => $viewModel->emailValidated
        ];
    }
}
