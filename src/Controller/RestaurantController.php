<?php
namespace App\Controller;


use App\Entity\Restaurant;
use App\Entity\TypePlat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
/**
 * Description of RestaurantController
 *
 * @author lgaray
 */
class RestaurantController extends AbstractController{
        
    #[Route("client/monPanier",name:"panier")]
    public function panier(EntityManagerInterface $entityManager,Request $request){
        $lesPlatsCommmande=$request->getSession()->get('platCommande');
        
        return $lesPlatsCommmande;
    }
    
    #[Route("client/restaurant/liste",name:"liste_restaurant_client")]
    public function liste(EntityManagerInterface $entityManager,Request $request){
        $lesRestaurants = $entityManager->getRepository(Restaurant::class)->findAll();
        
        $lesTypesPlats=$entityManager->getRepository(TypePlat::class)->findAll();
        $choixTypePlat=array();
        $choixResto=array();
        //améliorer formulaire si possible--------------------------------------------------------------------------
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
        //si connecté
        if($this->getUser()){
            return $this->render('client/restaurant/listeRestaurants.html.twig', [
                        'lesRestaurants' => $lesRestaurants,
                        'formSearch' => $formSearch,
            ]);
        //si pas connecté
        }else{
            return $this->render('visiteur/restaurant/listeRestaurants.html.twig', [
                'lesRestaurants' => $lesRestaurants,
                'formSearch' => $formSearch,
            ]);
        }
        
    }

    
    #[Route("client/restaurant/{id}",name:"detail_restaurant_client")]
    public function detail(EntityManagerInterface $entityManager,$id,Request $request){
        $leRestaurant=$entityManager->getRepository(Restaurant::class)->find($id);
        $nvlleCommande=false;
        
        $commande=$request->getSession()->get('platCommande');

        //SI CONNECTé
        if($this->getUser()){
            return $this->render("client/restaurant/detailRestaurant.html.twig", [
                        'leRestaurant' => $leRestaurant,
                        'nvlleCommande'=>$nvlleCommande
            ]);
        //si pas connecté
        }else{
            return $this->render("visiteur/restaurant/detailRestaurant.html.twig", [
                'leRestaurant' => $leRestaurant,
                'nvlleCommande'=>$nvlleCommande
            ]);
        }
    }
    
    #[Route("client/searchRestaurant/{nomR}",name:"search_restaurant")]
    public function searchRestaurant(EntityManagerInterface $entityManager,$nomR){
        $lesRestaurants=$entityManager->getRepository(Restaurant::class)->getRestoByName($nomR);
        $lesTypesPlats=$entityManager->getRepository(TypePlat::class)->findAll();
        $choixTypePlat=array();
        $choixResto=array();
        //améliorer formulaire si possible--------------------------------------------------------------------------
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
        
        return $this->render('client/restaurant/listeRestaurants.html.twig', [
                    'lesRestaurants' => $lesRestaurants,
                    'formSearch' => $formSearch,
        ]);
       
    }    
}
