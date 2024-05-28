<?php //
namespace App\Controller;


use App\Entity\Plat;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Form\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\QuantitePlat;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ModifierProfilFormType;
/**
 * Description of ClientController
 *
 * @author lgaray
 */
class ClientController extends AbstractController
{
    //put your code here
    #[Route("/client/home",name:"home_client")]
    public function home(EntityManagerInterface $entityManager){
        $lesPlats=$entityManager->getRepository(Plat::class)->getRandomPlat(5);
        
        //getRandomPlat retourne un tableau de tableau associatifs contenant l'id de chaque plat récuperé
        //on parcours ainsi $lesPlats pour y mettre des objets Plats avec l'id correspondant.
        for ($i=0;$i<count($lesPlats);$i++) {
            $id=$lesPlats[$i]['id'];
            $lesPlats[$i]=$entityManager->getRepository(Plat::class)->find($id);
        }
        if($this->getUser()){
            return $this->render('client/home.html.twig', [
                        'lesPlats' => $lesPlats,
            ]);
        }else{
            return $this->render('visiteur/home.html.twig', [
                'lesPlats' => $lesPlats,
            ]);
        }
    }
    
    #[Route("/client/ajouterCommande/{id}",name:"ajouter_commande")]
    public function ajouterCommande(Request $request,EntityManagerInterface $entityManager,$id){
        $session=$request->getSession();
        $lePlat=$entityManager->getRepository(Plat::class)->find($id);
        if ($session->has("platCommande")) {
            $commande = $session->get("platCommande");
            $present = false;
            
            
            for ($i = 0; $i < count($commande); $i++) {
                if ($commande[$i]->getLePlat()->getNomPlat() == $lePlat->getNomPlat()) {
                    $commande[$i]->setQuantite($commande[$i]->getQuantite() + 1);
                    $present=true;
                }
            }
                
            if($present===false) {
                $QuantitePlat = new QuantitePlat();
                $QuantitePlat->setLePlat($lePlat);
                $commande[] = $QuantitePlat;
            }
        }
        else{
            $commande=array();
            $session->set("leResto",$lePlat->getLeRestaurant()->getNomRestaurant());            
            $QuantitePlat = new QuantitePlat();
            $QuantitePlat->setLePlat($lePlat);
            
            $commande[]=$QuantitePlat;    
        }
        $session->set("platCommande",$commande);

        
        return $this->redirectToRoute('detail_restaurant_client',['id' => $lePlat->getLeRestaurant()->getId()]);
    }
    
    #[Route("/client/Nvllecommande/{id}",name:"commencerNvlle_commande")]
    public function commencerNvlle_commande(Request $request,$id){ 
        $session=$request->getSession()->clear();
        
        return $this->redirectToRoute('ajouter_commande',['id'=>$id]);
    } 
    
    
    
    #[Route("/client/commande",name:"consulter_commande")]
    public function consulterCommande(Request $request,EntityManagerInterface $entityManager){        
        $commande=$request->getSession()->get("platCommande");
        $prix=0;
        
        $form=$this->createForm(CommandeType::class);
        
        $form->handleRequest($request);
                
        if ($form->isSubmitted() && $form->isValid()) {      
            return $this->validerCommande($request,$entityManager,$form);
        }
        
        if($commande != null){
            foreach ($commande as $quantitePlat){
                $prix+=($quantitePlat->getLePlat()->getPrixClientPlat()*$quantitePlat->getQuantite());
            }
        }
        
        //si connecte
        if ($this->getUser()){
            return $this->render('client/restaurant/commande.html.twig',[
                'commande'=>$commande,
                'prix'=>$prix,
                'form'=>$form->createView(),
                'user'=> $this->getUser(),
            ]);
            //si pas connecté, mais ça bug
        }else{
            return $this->render('visiteur/restaurant/commande.html.twig',[
                'commande'=>$commande,
                'prix'=>$prix,
                'form'=>$form->createView(),
                'user'=> $this->getUser(),
            ]);
        }
        
    }

    #[Route("/client/commande/valider", name: "valider_commande")]
    public function validerCommande(Request $request, EntityManagerInterface $entityManager,$form) {
        if($this->getUser() == null){
            return $this->redirectToRoute("app_login");
        }
        
        $commande = new Commande();

        $commande=$form->getData();
                
        $dateCommande = new DateTime();
        $dateCommande->format('Y-m-d H:i:s');

        $commande->setDateCommande($dateCommande);
        $entityManager->persist($commande);
        $entityManager->flush();

        $this->validerQuantitePlat($request, $commande, $entityManager);

        $QuantitesPlats = new ArrayCollection($request->getSession()->get("platCommande"));

        $commande->setUnUtilisateur($this->getUser());
        $commande->setLesQuantitesPlats($QuantitesPlats);

        $entityManager->persist($commande);
        $entityManager->flush();

        $request->getSession()->clear();

        return $this->redirectToRoute('consulter_commande');
    }
    
    public function validerQuantitePlat(Request $request,Commande $commande,EntityManagerInterface $entityManager){
        $panier=$request->getSession()->get("platCommande");
        foreach ($panier as $quantitePlats){
            $quantitePlats->setUneCommande($commande);
            $quantitePlats->setLePlat($entityManager->getRepository(Plat::class)->find($quantitePlats->getLePlat()->getId()));
            $entityManager->persist($quantitePlats);
            $entityManager->flush();
        }
        
    }    
    
    //---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
        //acceder a mon profil
    #[Route("/client/mon_profil",name:"profil_client")]
    public function monProfil(EntityManagerInterface $entityManager){
        //OBTENIR MON USER
        $user=$this->getUser();
        
        $lesCommandes=$entityManager->getRepository(Commande::class)->getCommandesByDate($user);
        $prixTotaux=$entityManager->getRepository(Commande::class)->calculerPrixCommande();
        

        //CHARGER profil.html avec le user
        return $this->render('client/profil.html.twig', [
            'user'=>$user,
            'lesCommandes'=>$lesCommandes,
            'prixTotaux'=>$prixTotaux,
        ]);
    }
    
    
    #[Route("/client/modifier_profil",name:"profil_client_modifier")]
    public function modifierProfil(Request $request,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager){
        $user=$this->getUser();
        $form = $this->createForm(ModifierProfilFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //nom utilisateur
            $user->setNomUtilisateur($form->get("nomUtilisateur")->getData());
            //prenom
            $user->setPrenomUtilisateur($form->get("prenomUtilisateur")->getData());
            //ville
            $user->setVilleUtilisateur($form->get("villeUtilisateur")->getData());
            //rue
            $user->setRueUtilisateur($form->get("rueUtilisateur")->getData());
            //codepostal
            $user->setCpUtilisateur($form->get("cpUtilisateur")->getData());
            //numero de rue
            $user->setNumRueUtilisateur($form->get("numRueUtilisateur")->getData());
            
            //mail
            $user->setRueUtilisateur($form->get("rueUtilisateur")->getData());
            //PASSWORD
            
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('home_client');
        }
        
        return $this->render('client/modifier_profil.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    //suprimmer nom compte (confirmation)
    #[Route("/client/delete_mon_profil",name:"profil_client_delete")]
    public function demanderDeleteMonProfil(EntityManagerInterface $entityManager){
        //OBTENIR MON USER
        $user=$this->getUser();
        //CHARGER profil.html avec le user
        return $this->render('client/suppression_confirmation.html.twig', [
                    'user' => $user,
        ]);
    }
    
    //apres avoir cliqué sur oui, executer celui ci
    #[Route("/client/delete_mon_profil_go_ahead",name:"delete_go_ahead")]
    public function deleteGoAhead(EntityManagerInterface $entityManager){
        //OBTENIR MON USER
        $user=$this->getUser();
        $user->setSupprimer(true);
        $entityManager->persist($user);
        $entityManager->flush();
        //CHARGER profil.html avec le user
        return $this->redirectToRoute("index_general");
    }
}
