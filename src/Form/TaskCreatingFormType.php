<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class TaskCreatingFormType extends TaskFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('name')
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new Length(min: 3, max: 255),
                ],
            ]);
    }
}
