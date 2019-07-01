<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
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
            ->add('imageName', null, ['label' => 'Nom de l\'image avec l\'extention'])
            ->add('createdAt', null, ['label' => 'Création du prdotui à :'])
            ->add('category', null, ['label' => 'Category de votre produit'])
            //->add('tags')
            ->add('submit', SubmitType::class, ['label' => 'Créer un produit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
