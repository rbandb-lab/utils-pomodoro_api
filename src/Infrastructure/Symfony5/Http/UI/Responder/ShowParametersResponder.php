<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Responder;

final class ShowParametersResponder extends ApiResponder
{
    public function viewModelData($viewModel): array
    {
        return $viewModel->parameters;
    }
}
