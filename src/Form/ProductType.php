<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productName', null, ['label' => 'Nom du produit'])
            ->add('productDescription', null, ['label' => 'Description du produit'])
            ->add('price', null, ['label' => 'Prix'])
            ->add('isPublished', null, ['label' => 'Voulez vous publier ce produit ?'])
            ->add('ImageFile', FileType::class, [
                'label' => 'Choisir une image'
            ])
            ->add('createdAt', null, ['label' => 'Création du prdotui à :'])
            ->add('category', null, ['label' => 'Category de votre produit'])
            //->add('tags')
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un produit',
                'attr' => [
                    'class' => 'btn btn-outline-success'
                ]
            ])
            /*->add('tags', CollectionType::class, [
                'entry_type' => TagType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true
            ])*/ // Pour le systéme d'ajout e tde suppression des tags,
                //  mais ne nous prenons pas la tête
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
