<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony5\Http\UI\Validation\Dto\ValidateEmailDto;

final class EmailValidationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => ValidateEmailDto::class
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('token', TextType::class, [
            'required' => false,
            'constraints' => [
                new Assert\NotBlank()
            ]
        ]);
    }
}
