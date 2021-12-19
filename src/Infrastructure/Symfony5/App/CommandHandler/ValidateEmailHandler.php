<?php

declare(strict_types=1);

namespace Symfony5\App\CommandHandler;

use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmail;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailPresenter;
use Pomodoro\Domain\Worker\UseCase\ValidateEmail\ValidateEmailRequest;
use Symfony5\Http\UI\Validation\Dto\ValidateEmailDto;

final class ValidateEmailHandler
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function handle(ValidateEmailDto $dto, ValidateEmailPresenter $presenter): ValidateEmailPresenter
    {
        $validateEmailRequest = new ValidateEmailRequest();
        $validateEmailRequest->withTokenString($dto->token);
        $validate = new ValidateEmail($this->workerRepository);
        $validate->execute($validateEmailRequest, $presenter);

        return $presenter;
    }
}
