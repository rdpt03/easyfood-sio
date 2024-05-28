<?php
            //installer composer require intervention/image

namespace App\Controller;

use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestaurateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Entity\TypePlat;
use App\Entity\Utilisateur;
use App\Form\PlatType;
use App\Form\ModPlatType;
use App\Form\RestaurantType;
use App\Form\TypePlatType;
use App\Form\ChoiceType;

use Symfony\Component\HttpKernel\KernelInterface;

class RestaurateurController extends AbstractController {
    private $pathActuel;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->pathActuel = $kernel->getProjectDir();
    }

    #[Route("/restaurateur/home", name: "home_restaurateur")]
    public function home(EntityManagerInterface $entityManager) {


        return $this->render('restaurateur/home.html.twig', [
        ]);
    }

    //////// FONCTIONNALITE PLAT \\\\\\\\\\\\\
    #[Route("/restaurateur/newplat", name: "new_plat")]
    public function newPlat(EntityManagerInterface $entityManager, Request $request) {
        $form = $this->createForm(PlatType::class);

        $mesRestos = $this->getUser()->getLesRestaurants();
        $choix = [];
        foreach ($mesRestos as $restaurant) {
            $choix[$restaurant->getNomRestaurant()] = $restaurant;
        }

        $lesTypesPlats = $entityManager->getRepository(TypePlat::class)->findAll();
        $choixTypesPlats = [];
        foreach($lesTypesPlats as $typePlat){
            $choixTypesPlats[$typePlat->getLibelleTypePlat()] = $typePlat;
        }

        $form = $this->createForm(PlatType::class, null, [
            'restaurants' => $choix, // Envoyer les restaurants disponibles au formulaire
            'typesPlats' => $choixTypesPlats,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $unPlat = $form->getData();
            $entityManager->persist($unPlat);
            $entityManager->flush();
            $imageFile = $form['photoPlat']->getData();
            /*
            if ($imageFile) {
                // Générer le nom du fichier basé sur l'ID du plat
                $imageName = $unPlat->getId() . '.jpeg';

                // Chemin complet vers le dossier de stockage
                $imagePath = $this->pathActuel . 'public/element/plats/' . $imageName;

                // Redimensionner et sauvegarder l'image
                $imageManager = new ImageManager(array('driver' => 'gd'));
                $image = $imageManager->make($imageFile)->fit(300, 300);
                $image->save($imagePath);
                
                // Associer l'image au plat
                $unPlat->setPhotoResto($imageName);
            }
            */
            
            if ($imageFile instanceof UploadedFile) {
                // Générer le nom du fichier basé sur l'ID du plat
                $imageName = $unPlat->getId() . '.png';

                // Chemin complet vers le dossier de stockage
                $imagePath = $this->pathActuel . '/public/element/plat/';

                $imageFile->move($imagePath,$imageName);
                
                // Associer l'image au plat
                $unPlat->setPhotoPlat($imageName);
            }
            $entityManager->persist($unPlat);
            $entityManager->flush();

            return $this->redirectToRoute('liste_plats', ['id' => $unPlat->getId()]);
        }

        return $this->render('restaurateur/plat/newPlat.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    #[Route("/restaurateur/updplat/{id}", name: "upd_plat")]
    public function updPlat(EntityManagerInterface $entityManager, $id, Request $request) {
        //$entityManager = $this->getDoctrine()->getManager();
        $unPlat = $entityManager->getRepository(Plat::class)->find($id);
        

        $mesRestos = $this->getUser()->getLesRestaurants();
        $choix = [];
        foreach ($mesRestos as $restaurant) {
            $choix[$restaurant->getNomRestaurant()] = $restaurant;
        }

        $lesTypesPlats = $entityManager->getRepository(TypePlat::class)->findAll();
        $choixTypesPlats = [];
        foreach($lesTypesPlats as $typePlat){
            $choixTypesPlats[$typePlat->getLibelleTypePlat()] = $typePlat;
        }


        $form = $this->createForm(ModPlatType::class, $unPlat, [
            'restaurants' => $choix, // Envoyer les restaurants disponibles au formulaire
            'typesPlats' => $choixTypesPlats,
        ]);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             $imageFile = $form['imageFile']->getData();

            // Gestion de l'image
            if ($imageFile) {
                // Générer le nom du fichier basé sur l'ID du plat
                $imageName = $unPlat->getId() . '.jpeg';

                // Chemin complet vers le dossier de stockage
                $imagePath = $this->getParameter('images_directory') . '/' . $imageName;

                // Redimensionner et sauvegarder l'image
                $imageManager = new ImageManager();
                $image = $imageManager->make($imageFile)->fit(300, 300);
                $image->save($imagePath);

                // Associer l'image au plat
                $unPlat->setPhotoPlat($imageName);
            }
            
            $entityManager->persist($unPlat);            
            $entityManager->flush();
            return $this->redirectToRoute('detail_plat', array('id' => $unPlat->getId()));
        }
        return $this->render('restaurateur/plat/updPlat.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    #[Route("/restaurateur/detailplat/{id}", name: "detail_plat")]
    public function detailPlat(EntityManagerInterface $entityManager, $id) {
        //$entityManager = $this->getDoctrine()->getManager();
        $unPlat = $entityManager->getRepository(Plat::class)->find($id);

        return $this->render('restaurateur/plat/detailPlat.html.twig', array(
                    'unPlat' => $unPlat,
        ));
    }

    #[Route("/restaurateur/delplat/{id}", name: "del_plat")]
    public function delPlat(EntityManagerInterface $entityManager, $id) {
        $unPlat = $entityManager->getRepository(Plat::class)->find($id);
        $entityManager->remove($unPlat);
        $entityManager->flush();

        return $this->redirectToRoute('liste_plats');
    }

    ////PAS FINI
    #[Route("/restaurateur/listeplat", name: "liste_plats")]
    public function listePlatDeMonResto(EntityManagerInterface $entityManager) {
        $user = $this->getUser();
        $mesRestaurants = $user->getLesRestaurants();

        $platsParRestaurant = [];

        foreach ($mesRestaurants as $restaurant) {
            $lesPlats = $restaurant->getLesPlats();

            $platsParRestaurant[$restaurant->getId()] = $lesPlats->toArray();
        }

        return $this->render('restaurateur/plat/listePlats.html.twig', [
                    'mesRestaurants' => $mesRestaurants,
        ]);
    }

    //////// FONCTIONNALITE RESTAURANT \\\\\\\\\\\\\
    #[Route("/restaurateur/newrestaurant", name: "new_restaurant")]
    public function newRestaurant(EntityManagerInterface $entityManager, Request $request) {
        $form = $this->createForm(RestaurantType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //creer un resto
            //$unRestaurant = new Restaurant();
            //obtenir les valeurs dans le form
            $unRestaurant = $form->getData();
            //sauvegarder afin d'obtenir l id
            $entityManager->persist($unRestaurant);
            $entityManager->flush();

            //set l'user
            $unRestaurant->setLUtilisateur($this->getUser());
            //set l'image
            $imageFile = $form['photoResto']->getData();
            if ($imageFile instanceof UploadedFile) {
                // Générer le nom du fichier basé sur l'ID du plat
                $imageName = $unRestaurant->getId() . '.png';

                // Chemin complet vers le dossier de stockage
                $imagePath = $this->pathActuel . '/public/element/restaurant/';

                $imageFile->move($imagePath,$imageName);
                
                // Associer l'image au plat
                $unRestaurant->setPhotoResto($imageName);
            }
            //sauvegarder
            $entityManager->persist($unRestaurant);
            $entityManager->flush();
            //return $this->redirectToRoute('liste_restaurants', array('id' => $unRestaurant->getId()));
        }
        return $this->render('restaurateur/restaurant/newRestaurant.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    #[Route("/restaurateur/updrestaurant/{id}", name: "upd_restaurant")]
    public function updRestaurant(EntityManagerInterface $entityManager, $id, Request $request) {
        //$entityManager = $this->getDoctrine()->getManager();
        $unRestaurant = $entityManager->getRepository(Restaurant::class)->find($id);

        $form = $this->createForm(RestaurantType::class, $unRestaurant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($unRestaurant);
            $entityManager->flush();
            return $this->redirectToRoute('detail_restaurant', array('id' => $unRestaurant->getId()));
        }
        return $this->render('restaurateur/restaurant/updRestaurant.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    #[Route("/restaurateur/detailrestaurant/{id}", name: "detail_restaurant")]
    public function detailRestaurant(EntityManagerInterface $entityManager, $id) {
        //$entityManager = $this->getDoctrine()->getManager();
        $unRestaurant = $entityManager->getRepository(Restaurant::class)->find($id);

        return $this->render('restaurateur/restaurant/detailRestaurant.html.twig', array(
                    'unRestaurant' => $unRestaurant,
        ));
    }

    #[Route("/restaurateur/delrestaurant/{id}", name: "del_restaurant")]
    public function delRestaurant(EntityManagerInterface $entityManager, $id) {
        $unRestaurant = $entityManager->getRepository(Restaurant::class)->find($id);
        $entityManager->remove($unRestaurant);
        $entityManager->flush();

        return $this->redirectToRoute('liste_restaurants');
    }

    #[Route("/restaurateur/listerestaurants", name: "liste_restaurants")]
    public function listeRestaurants(EntityManagerInterface $entityManager) {
        $mesRestaurants = $this->getUser()->getLesRestaurants();

        return $this->render('restaurateur/restaurant/listeRestaurants.html.twig', [
                    'mesRestaurants' => $mesRestaurants,
        ]);
    }

    //////// FONCTIONNALITE TYPEPLAT \\\\\\\\\\\\\

    #[Route("/restaurateur/newtypeplat", name: "new_type_plat")]
    public function newTypePlat(EntityManagerInterface $entityManager, Request $request) {
        $form = $this->createForm(TypePlatType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $unTypePlat = new TypePlat();
            $unTypePlat = $form->getData();
            //$entityManager = $this->getDoctrine()->getManager(); //--
            $entityManager->persist($unTypePlat);
            $entityManager->flush();
            return $this->redirectToRoute('liste_plats');
        }
        return $this->render('restaurateur/typeplat/newTypePlat.html.twig', array(
                    'form' => $form->createView(),
        ));
    }
    
    
    //////// FONCTIONNALITE UPLOAD IMAGE \\\\\\\\\\\\\


    
    #[Route("/upload", name: "image_upload")]     
     public function upload(Request $request)
    {
        // Création du formulaire pour le téléchargement de l'image
        $form = $this->createFormBuilder()
            ->add('imageFile', FileType::class)
            ->getForm();

        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            // Vérification du type de fichier
            if ($imageFile->guessExtension() === 'jpeg') {
                // Création d'une instance de la classe ImageManager de Intervention Image
                $imageManager = new ImageManager();

                // Redimensionnement de l'image
                $image = $imageManager->make($imageFile)->fit(300, 300);

                // Génération d'un nom de fichier unique
                $nouveauNomFichier = uniqid().'.jpeg';

                try {
                    // Sauvegarde de l'image redimensionnée dans le répertoire cible (/element)
                    $image->save($this->getParameter('images_directory') . '/' . $nouveauNomFichier);
                } catch (FileException $e) {
                    // Gestion de l'erreur (par exemple, journalisation ou message d'erreur)
                }

                // Enregistrement de $nouveauNomFichier dans la base de données associée au restaurateur

                // Redirection ou affichage de confirmation
            } else {
                // Gestion de l'erreur si le type de fichier n'est pas JPEG
            }
        }

        return $this->render('image_upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

    
    
    
    
    
    
    
    
    
    
 

