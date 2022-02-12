<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Responder;

final class UpdateParametersResponder extends ApiResponder
{
    public function viewModelData($viewModel): array
    {
        return [
            'id' => $viewModel->id,
            'parameters' => $viewModel->parameters
        ];
    }
}
