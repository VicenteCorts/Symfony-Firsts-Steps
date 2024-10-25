<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnimalType extends SAbstractType {
    public function builForm(FormBuilderInterface $builder, array $options) {
        $builder->add('tipo', TextType::class)
                ->add('cantidad', NumberType::class)
                ->add('raza', TextType::class)
                ->add('color', TextType::class)
                ->add('submit', SubmitType::class, [
                    'label' => 'Crear Animal',
                    'attr' => ['class' => 'btn']
                ]);
    }
}