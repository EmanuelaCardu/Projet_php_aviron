<?php

namespace App\Controller;


use App\Entity\CourEau;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\DependencyInjection\Loader\Configurator\request;

class HomeController extends AbstractController
{
    #[Route('/accueil/user', name: 'accueil_user')]
    public function AfficheKmUser(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT km FROM App\Entity\User km");
        $resultat = $query->getResult();
     

  
        $query = $em->createQuery("SELECT cour FROM App\Entity\CourEau cour");
        $resultat2 = $query->getResult();


        // separer en valides et non validees
        $coursValidees = [];
        $coursNonValidees = [];
        $user = $this->getUser();

        foreach ($resultat2 as $cours) {
            // voir si le user connecte est dans la liste de users de cette cours
            if ($cours->getUser()->contains($user)) {
                $coursValidees[] = $cours;
            } else {
                // l'user n'est pqs dans la liste
                $coursNonValidees[] = $cours;
            }
        }
        // dump($coursNonValidees);
        // dd($coursValidees);

        $vars = [
            'km' => $resultat,
            'coursValidees' => $coursValidees,
            'coursNonValidees' => $coursNonValidees
        ];

        return $this->render('accueil/accueil_user.html.twig', $vars);
    }




    #[Route('/', name: 'home')]
    public function homeAfficheCours(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT cour FROM App\Entity\CourEau cour ORDER BY cour.distance ASC ");
        $resultat = $query->getResult();
        $vars = ['cours' => $resultat];
        
        return $this->render('home/home.html.twig', $vars);
    }

    #[Route('/form/km/saisis', name: 'form_km_saisis')]
    public function recupKmForm(Request $req, ManagerRegistry $doctrine)
    {


        $km = $req->request->get('km');

        $user = $this->getUser();
        $user->setKm($user->getKm() + $km);
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['km' => $user->getKm()]);
    }


    #[Route('/form/affiche/km')]
    public function formAfficheKm()
    {
        
        return $this->render("/accueil_user.html.twig");
    }


    #[Route('/afficher/photo', name: 'photo')]
    
    public function affichePhoto(Request $request, ManagerRegistry $doctrine)
    {
        // obtenir l'id du form post qu'on envoie depuis la vue (axios)
        $id = $request->request->get('id');
   

        //prendre ici les photos qui sont liés à l'id des CourEaux
        // prendre le cours d'eau de la bd find($id)
          // Utilisez l'EntityManager pour accéder à la base de données.
        $em = $doctrine->getManager();

         // Utilisez la méthode find pour récupérer l'objet CoursEau correspondant à l'ID.
        $CourEau = $em->getRepository(CourEau::class)->find($id);


         // accéder aux propriétés de $coursEau et les passer à votre vue.
        $nom = $CourEau->getNom();
        $description = $CourEau->getDescription();
        $distance = $CourEau->getDistance();
        $id = $CourEau->getId();

        // $cours->getPhoto() // ATTENTION CEST UNE COLLECTION
        $photos = $CourEau->getPhoto();

        //tableau pour stocker les informations des photos
        $photosInfos = [];
        foreach ($photos as $photo) {
            $photosInfos[] = [
                'id' => $photo->getId(),
                'description' => $photo->getDescription(),
                'lien' => $photo->getLien(),
                
            ];
        }

        // renvoyer les photos a la vue dans un JsonResponse

        $response = new JsonResponse([
            'nom' => $nom,
            'description' => $description,
            'photos' => $photosInfos,
        ]);

        return $response;

        
    }

   


    //ici je prend tous les cours pour les afficher dans le accordion2
    #[Route('/accueil/useraccordion', name: 'accordion')]
        public function accordionCours(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT cour FROM App\Entity\CourEau cour");
        $resultat = $query->getResult();


        // separer en valides et non validees
        $coursValidees = [];
        $coursNonValidees = [];
        $user = $this->getUser();

        foreach ($resultat as $cours) {
            // voir si le user connecte est dans la liste de users de cette cours
            if ($cours->getUser()->contains($user)) {
                $coursValidees[] = $cours;
            } else {
                // l'user n'est pqs dans la liste
                $coursNonValidees[] = $cours;
            }
        }
        // dump($coursNonValidees);
        // dd($coursValidees);

        $vars = [
            'coursValidees' => $coursValidees,
            'coursNonValidees' => $coursNonValidees
        ];



        return $this->render('accueil/accueil_user_accordion.html.twig', $vars);
        
    }



// #[Route('/accueil/useraccordion', name: 'accordion')]




//     public function accordionCours(ManagerRegistry $doctrine)
// {
//     $em = $doctrine->getManager();
//     $user = $this->getUser(); // Obtenez l'utilisateur connecté

//     // Récupérez l'ID du cours d'eau à valider (vous devez passer cet ID depuis le front-end)
//     $coursId = $request->request->get('id');

//     // Récupérez le cours d'eau à partir de l'ID
//     $cours = $em->getRepository(CourEau::class)->find($coursId);

//     // Assurez-vous que le cours d'eau existe
//     if ($cours) {
//         // Associez le cours d'eau à l'utilisateur
//         $cours->addUser($user);

//         // Marquez le cours d'eau comme validé
//         $cours->setValide(true);

//         // Enregistrez les modifications dans la base de données
//         $em->flush();
//     }

//     // Rechargez les listes de cours d'eau validés et non validés
//     $coursValidees = [];
//     $coursNonValidees = [];

//     // ... (Votre code pour séparer les cours d'eau en deux listes)

//     // Passez les listes mises à jour au modèle Twig
//     $vars = [
//         'coursValidees' => $coursValidees,
//         'coursNonValidees' => $coursNonValidees
//     ];

//     return $this->render('accueil/accueil_user_accordion.html.twig', $vars);
// }

}