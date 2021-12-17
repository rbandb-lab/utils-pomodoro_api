<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation\Form;

use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class InterruptionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => InterruptionDto::class
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taskName', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255])
                ]
            ]);
    }
}
