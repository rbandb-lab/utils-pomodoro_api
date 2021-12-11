<?php

namespace Symfony5\Http\UI\Validation;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

trait InputValidationTrait
{
    private array $formErrors = [];

    private FormInterface $form;

    private function validate(Request $request, string $formType, ?bool $clearMissing = true): bool
    {
        switch ($request->getMethod()) {
            case 'POST':
            $requestData = json_decode($request->getContent(), true);
            break;

            case 'GET':
            $requestData = $request->query->all();
            break;
        }

        $form = $this->formFactory->create($formType);
        $form->submit($requestData, $clearMissing);

        if ($form->isSubmitted() && !$form->isValid()) {
            $helper = new FormErrorsHelper();
            $this->formErrors = $helper->getErrorsFromForm($form);
            return false;
        }

        $this->form = $form;
        return true;
    }
}
