<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Evenement;
use App\Entity\User;
use StreamToFileTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom de l\'évènement',
                'label_attr'=>['class'=>'form-control-label text-light'],
                'attr'=>['class'=> 'form-control']
            ])
            ->add('lieu',TextType::class,[
                'label'=>'Lieu de l\'évènement',
                'label_attr'=>['class'=>'form-control-label text-light'],
                'attr'=>['class'=> 'form-control']
            ])
            ->add('date_deb',DateTimeType::class,[
                'label'=>'Date de début de l\'évènement',
                'label_attr'=>['class'=>'form-control-label text-light'],
                'attr'=>['class'=> 'form-control'],
            ])
            ->add('date_fin',DateTimeType::class,[
                'label'=>'Date de fin de l\'évènement',
                'label_attr'=>['class'=>'form-control-label text-light'],
                'attr'=>['class'=> 'form-control']
            ])
            ->add('picture',FileType::class,[
                'label'=>'Image',
                'required'=>false,
                'data_class'=>null,
                'attr'=>['class'=> 'form-control'],
                'label_attr'=>['class'=>'form-control-label text-light'],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Veuillez envoyer un fichier image valide',
                    ])
                ],
            ])
            ->add('category', EntityType::class, [
                'attr'=>['class'=> 'form-control'],
                'label'=>'Catégories',
                'label_attr'=>['class'=>'form-control-label text-light'],
                'class' => Category::class,
                'choice_label' => 'name',
            ])
        ;
        
        
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
