<?php

namespace App\Form;

use App\Entity\Portfolio;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PortfolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
                // We check if it's an adding or editing action to put an attribyte "required" on the input if necessary
                $portfolio = $event->getData();
                $form = $event->getForm();

                // If $portfolio is null, it's an adding action and the picture field is required
                if ($portfolio->getId()) {
                    $form->add('picture', FileType::class, [
                        'label' => 'Photo',
                        'mapped' => false,
                        'required' => true,
                        'constraints' =>[
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
                        'constraints' =>[
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
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('thumbnail')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
