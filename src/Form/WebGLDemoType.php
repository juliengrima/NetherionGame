<?php

namespace App\Form;

use App\Entity\Games;
use App\Entity\WebGLDemo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class WebGLDemoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name')
        ->add('game', EntityType::class, [
            'class' => Games::class, // Spécifie l'entité liée
            'choice_label' => 'name', // Affiche uniquement le champ `name`
        ])
        // ->add('link', TextType::class, [
        //     'label' => 'Lien',
        // ])
        ->add('dataFile', FileType::class, [
            'label' => 'Fichier .data (Unity WebGL)',
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '10G', // Limite à 10 Go
                    'mimeTypes' => [
                        'application/octet-stream', // Type MIME générique pour les fichiers binaires
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger un fichier .data valide.',
                    'maxSizeMessage' => 'La taille du fichier ne peut pas dépasser 10 Go.', // Message personnalisé
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebGLDemo::class,
        ]);
    }
}
