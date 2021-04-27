<?php

namespace App\Form;

use App\Entity\Photography;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotographyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('textualAlternative')
            ->add('picture')
            ->add('thumbnail')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('portfolio')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photography::class,
        ]);
    }
}
