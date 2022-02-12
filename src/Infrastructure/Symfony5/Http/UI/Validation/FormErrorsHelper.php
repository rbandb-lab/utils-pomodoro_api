<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation;

use Symfony\Component\Form\FormInterface;

class FormErrorsHelper
{
    public function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childErrors = $this->getErrorsFromForm($childForm)) {
                $errors[$childForm->getName()] = $childErrors;
            }
        }

        return $errors;
    }
}
