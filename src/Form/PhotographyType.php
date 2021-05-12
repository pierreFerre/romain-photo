<?php

namespace App\Form;

use App\Entity\Portfolio;
use App\Entity\Photography;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Repository\PortfolioRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PhotographyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                // We check if it's an adding or editing action to put an attribyte "required" on the input if necessary
                $photography = $event->getData();
                $form = $event->getForm();

                // If $photography is null, it's an adding action and the picture field is required
                if ($photography->getId() === null) {
                    $form->add('picture', FileType::class, [
                        'label' => 'Photo',
                        'mapped' => false,
                        'required' => true,
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg'
                                ]
                            ])
                        ]
                    ]);
                } else {
                    // If it's an editing action, the picture field is not required
                    $form->add('picture', FileType::class, [
                        'label' => 'Photo',
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg'
                                ]
                            ])
                        ]
                    ]);
                }
            })
            ->add('title', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('textualAlternative', TextType::class, [
                'label' => 'Description alternative',
            ])
            ->add('portfolio', EntityType::class, [
                'label' => 'Collection',
                'class' => Portfolio::class,
                'choice_label' => 'name',
                'query_builder' => function (PortfolioRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'multiple' => false,
                'expanded' => false,
            ])
            // ->add('portfolio')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photography::class,
        ]);
    }
}
