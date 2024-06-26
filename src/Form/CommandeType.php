<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DatesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('dateLivrCommande')
                ->add('commentaireClientCommande')
                ->add('modeReglementCommande', ChoiceType::class, [
                    'choices' => [
                        'Paypal' => 'Paypal',
                        'CB' => 'Carte bancaire',
                    ],
                    'expanded' => true,
                    'multiple' => false,
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Confirmer la commande',
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
