<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Plat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPlat')
            ->add('prixFournisseurPlat')
            ->add('prixClientPlat')
            ->add('platVisible')
            ->add('photoPlat', FileType::class, [
                'label' => 'Image du plat (JPEG)',
                'required' => false, // Rendre le champ facultatif
            ])
            ->add('descriptionPlat')
            ->add('leTypePlat', ChoiceType::class, [
                'choices' => $options['typesPlats'],
                'label' => 'Choisir un type de plat : ',
                // Autres configurations éventuelles
            ])
            // Ajout du champ pour sélectionner le restaurant
            ->add('leRestaurant', ChoiceType::class, [
                'choices' => $options['restaurants'],
                'label' => 'Choisir un restaurant',
                // Autres configurations éventuelles
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
            'restaurants' => [], // Options par défaut pour les restaurants
            'typesPlats' => [],
        ]);
        $resolver->setAllowedTypes('restaurants', 'array');
    }
}
