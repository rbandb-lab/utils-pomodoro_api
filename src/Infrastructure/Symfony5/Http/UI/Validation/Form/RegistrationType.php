<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony5\Http\UI\Validation\Dto\RegistrationDto;

final class RegistrationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => RegistrationDto::class
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255])
                ]
            ])
            ->add('password', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 8])
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 120]),
                    new Assert\Email()
                ]
            ])
            ->add('pomodoroDuration', NumberType::class, [
                'required' => false
            ])
            ->add('shortBreakDuration', NumberType::class, [
                'required' => false
            ])
            ->add('longBreakDuration', NumberType::class, [
                'required' => false
            ])
            ->add('startFirstTaskIn', NumberType::class, [
                'required' => false
            ])
        ;
    }
}
