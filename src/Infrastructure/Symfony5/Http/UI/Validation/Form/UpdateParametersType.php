<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony5\Http\UI\Validation\Dto\UpdateParametersDto;

class UpdateParametersType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => UpdateParametersDto::class
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pomodoroDuration', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotNull()
                ]
            ])
            ->add('shortBreakDuration', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotNull()
                ]
            ])
            ->add('longBreakDuration', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotNull()
                ]
            ])
            ->add('startFirstTaskIn', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotNull()
                ]
            ]);
    }
}
