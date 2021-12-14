<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony5\Http\UI\Validation\Dto\AddUnplannedTaskDto;

final class AddUnplannedTaskType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => AddUnplannedTaskDto::class
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
            ])
            ->add('urgent', CheckboxType::class, [
                'required' => false,
                'empty_data' => false
            ])
            ->add('deadline', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
        ;
    }
}
