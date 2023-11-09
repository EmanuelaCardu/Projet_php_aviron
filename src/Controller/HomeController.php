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
    // on fait une requette à la bd pour recuperer le nom des cours d'eau et la distance
    #[Route('/', name: 'home')]
    public function homeAfficheCours(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT cour FROM App\Entity\CourEau cour ORDER BY cour.distance ASC ");
        $resultat = $query->getResult();
        $vars = ['cours' => $resultat];
        
        return $this->render('home/home.html.twig', $vars);
    }


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
            // voir si le user connecté est dans la liste de users de cet cours
            if ($cours->getUser()->contains($user)) {
                $coursValidees[] = $cours;
            } else {
                // l'user n'est pas dans la liste
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

        $em = $doctrine->getManager();
        $CourEau = $em->getRepository(CourEau::class)->find($id);


         // accéder aux propriétés de $coursEau et les passer à la vue.
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


    //on prend tous les cours pour les afficher dans le accordion
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
            // voir si le user connecté est dans la liste de users de cette cours
            if ($cours->getUser()->contains($user)) {
                $coursValidees[] = $cours;
            } else {
                // l'user n'est pas dans la liste
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

}