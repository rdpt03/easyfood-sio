<?php
namespace App\Controller;


use App\Entity\Plat;
use App\Entity\TypePlat;
use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Description of PlatController
 *
 * @author lgaray
 */
class PlatController extends AbstractController {

    #[Route("/client/plat/liste", name: "liste_plat")]
    public function liste(EntityManagerInterface $entityManager, Request $request) {
        $lesRestaurants = $entityManager->getRepository(Restaurant::class)->findAll();
        
        $lesTypesPlats=$entityManager->getRepository(TypePlat::class)->findAll();
        $choixTypePlat=array();
        $choixResto=array();
        foreach ($lesTypesPlats as $leTypePlat){
            $choixTypePlat[$leTypePlat->getLibelleTypePlat()] = " AND tp.id =".$leTypePlat->getId();
        }
        foreach ($lesRestaurants as $leResto) {
            $choixResto[$leResto->getVilleRestaurant()] = " AND r.villeRestaurant = '".$leResto->getVilleRestaurant()."'";
        }
        $choixFourchettePrix = [
        '€' => ' AND p.prixClientPlat <= 10 ',
        '€€' => ' AND p.prixClientPlat BETWEEN 10 AND 15 ',
        '€€€' => ' AND p.prixClientPlat BETWEEN 15 AND 20 ',
        '€€€€' => ' AND p.prixClientPlat > 20 ',
        ];



        $formSearch = $this->createFormBuilder()
                ->add('typePlat', ChoiceType::class, [
                    'choices' => $choixTypePlat,
                    'required' => false, 
                ])
                ->add('fourchettePrix', ChoiceType::class, [
                    'choices' => $choixFourchettePrix,
                    'required' => false, 
                ])
                ->add('villeResto', ChoiceType::class, [
                    'choices' => $choixResto,
                    'required' => false, 
                ])
                ->getForm();

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $recherche = $formSearch->getData();
            foreach ($recherche as $champ) {
                if($champ==null){
                    $champ="";
                }
            }
            $lesRestaurants=$entityManager->getRepository(Restaurant::class)->getRestoByR($recherche);
        }
        
        return $this->render('client/listePlat.html.twig', [
                    'lesRestaurants' => $lesRestaurants,
                    'formSearch' => $formSearch,
        ]);
    }
}
