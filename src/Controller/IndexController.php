<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class IndexController extends AbstractController {

    #[Route("/",name:"index_general")]
    public function index() {
        if ($this->getUser()!=null){
            if ($this->getUser()->getSupprimer()){
                return $this->redirectToRoute("app_logout");
            }
            if ($this->getUser()->getLeTypeUtilisateur()->getLibelleTypeUtilisateur() == "ROLE_CLIENT") {
                return $this->redirectToRoute("home_client");

                /*
            } elseif (in_array("ROLE_COMMERCIAL", $this->getUser()->getRoles())) {
                return $this->redirectToRoute("index_commercial");
            } elseif (in_array("ROLE_VENTE", $this->getUser()->getRoles())) {
                return $this->redirectToRoute("index_vente");*/
            } elseif ( $this->getUser()->getLeTypeUtilisateur()->getLibelleTypeUtilisateur() == "ROLE_RESTAURATEUR") {
            return $this->redirectToRoute("liste_restaurants");

            } else {
                return $this->render('default/index.html.twig', [
                            'message' => "Vous n'avez pas de rôle.",
                ]);
            }
        }
        else{//AMODIFIER
            return $this->redirectToRoute("app_login");
        }
    }

}
