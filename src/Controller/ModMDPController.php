<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ChangePassType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\NouvPassType;



class ModMDPController extends AbstractController{
    //fonction modifier le mot de passe
    #[Route('/client/mon_profil/modifier_mdp', name: 'modifier_mon_mdp')]
    public function newMDP(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher) {
        //obtenir user
        $user=$this->getUser();
        //si user connecté
        if ($user!=null){
            //obtenir le formulaire depuis la classe ChangePassType
            $form = $this->createForm(ChangePassType::class, $user);
            $form->handleRequest($request);
            
            //si le formulaire est envoyé et valide
            if ($form->isSubmitted() && $form->isValid()) {
                //recuperer le premier champs du nouveau mdp
                $newMDP1 = $form->get('newPlainPass1')->getData();
                //recuperer le deuxieme champs du nouveau mdp
                $newMDP2 = $form->get('newPlainPass2')->getData();

                //si les 2 mots de passes recuperés sont exactement egales : 
                if($newMDP1==$newMDP2){
                    //sauvegarder le mot de passe heché dans la base
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $newMDP1
                        )
                    );

                    //enregistrer les changements
                    $entityManager->persist($user);
                    $entityManager->flush();

                    //rediriger vers la page de mon profile
                    return $this->redirectToRoute("profil_client");
                }else{//si les 2 nouveaux mot de passes sont differents
                    //afficher le formulaire de changement de mot de passe avec un message d'erreur
                    return $this->render('security/new_mdp.html.twig',[
                        /*
                        "err"=>true,//activer le message d'erreur               //obsolete car ça vien d'u
                        "errmsg"=>"Le nouveau mot de passe ne correspond pas dans les 2 champs",//message
                        */
                        "changePassForm" => $form->createView()//formulaire
                    ]);
                }
            }
            //afficher le formulaire sans message d'erreur
            return $this->render('security/new_mdp.html.twig',[
                "err"=>false,//desactiver le message d'erreur
                "changePassForm" => $form->createView()//form
            ]);
        }else{//si l'user n'est pas connecté
            //aller vers index
            return $this->redirectToRoute("index_general");
        }
    }
}