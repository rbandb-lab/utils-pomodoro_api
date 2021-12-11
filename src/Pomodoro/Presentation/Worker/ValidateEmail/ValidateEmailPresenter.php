<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\ValidateEmail;

use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailPresenter as EmailValidationPresenterInterface;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailResponse;
use Pomodoro\Presentation\Worker\AbstractPresenter;
use Pomodoro\Presentation\Worker\Model\ValidateEmailViewModel;

class ValidateEmailPresenter extends AbstractPresenter implements EmailValidationPresenterInterface
{
    private ?ValidateEmailViewModel $viewModel = null;

    public function present(ValidateEmailResponse $response): void
    {
        $this->viewModel = new ValidateEmailViewModel();

        foreach ($response->errors as $error) {
            $this->viewModel->errors[$error->fieldName()] = $error->message();
        }

        $this->viewModel->emailValid = $response->emailValid;
    }

    public function viewModel(): ValidateEmailViewModel
    {
        return $this->viewModel;
    }
}
