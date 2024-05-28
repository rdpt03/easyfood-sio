<?php

namespace App\Form;

use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomRestaurant')
            ->add('numAdrRestaurant')
            ->add('rueAdrRestaurant')
            ->add('cpRestaurant')
            ->add('villeRestaurant')
            ->add('heureFermeture')
            ->add('heureOuverture')
            ->add('photoResto', FileType::class, [
                'label' => 'Image du plat (JPEG)',
                'required' => false, // Rendre le champ facultatif
            ])
            ;
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
