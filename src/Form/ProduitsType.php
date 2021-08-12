<?php

namespace App\Form;

use App\Entity\Produits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom')
            ->add('Reference')
            ->add('Categorie')
            ->add('Date_achat')
            ->add('Lieu_achat')
            ->add('Date_fin_garantie')
            ->add('Prix')
            ->add('Conseils')
            // ->add('Photo')
            // ->add('Manuel')
            // on ajoute le champ "images" dans le formulaire
            // il n'est pas lié à la basse de données (mapped à false)
            ->add('images', FileType::class, [
                'label' => 'Image :',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            // on ajoute le champ "fichiers" dans le formulaire
            // il n'est pas lié à la base de données (mapped à false)
            ->add('fichiers', FileType::class, [
                'label' => 'Manuel :',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
