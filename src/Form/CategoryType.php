<?php

namespace App\Form;

use App\Entity\Category;
use App\StreamToFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'attr'=>['class'=>'form-control'],
                'label'=>'Nom de la catégorie',
                'label_attr'=>['class'=>'form-control-label text-light']
            ])
            ->add('picture',FileType::class,[
                'attr'=>['class'=>'form-control'],
                'label'=>'Image',
                'required'=>false,
                'data_class'=>null,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
