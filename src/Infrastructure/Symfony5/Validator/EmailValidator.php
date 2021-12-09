<?php

declare(strict_types=1);

namespace Symfony5\Validator;

use Egulias\EmailValidator\EmailValidator as EguliasEmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Pomodoro\SharedKernel\Service\EmailValidator as EmailValidatorInterface;

class EmailValidator implements EmailValidatorInterface
{
    public function isValid(string $email): bool
    {
        $validator = new EguliasEmailValidator();

        return $validator->isValid($email, new RFCValidation());
    }
}
