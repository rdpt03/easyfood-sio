<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePassType extends AbstractType
{   



    //Fonction pour creer le formulaire de changement de mot de passe
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            //Champ Formulaire Nouveau Mot de Passe
            ->add('newPlainPass1', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    //Le mot de passe ne doit pas etre vide
                    new NotBlank([
                        'message' => 'Veuillez inserer un mot de passe',
                    ]),
                    //Contraintes sur la taille du mot de passe
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractéres.',
                        'max' => 100,
                        'maxMessage' => 'Votre mot de passe doit faire moins de {{ limit }} caractéres.',
                    ]),
                ],
            ])

            //Champ Formulaire Confirmer le mot de passe
            ->add('newPlainPass2', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    //Le mot de passe ne doit pas etre vide
                    new NotBlank([
                        'message' => 'Veuillez inserer un mot de passe',
                    ]),
                    //Contraine sur la taille des mots de passe
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractéres.',
                        'max' => 100,
                        'maxMessage' => 'Votre mot de passe doit faire moins de {{ limit }} caractéres.',
                    ]),
                ],
            ])
            
        ;
    }
    

    //Fonction pour configurer des options potentielles
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
