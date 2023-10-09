<?php

namespace App\Controller;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    #[Route('/accueil/user', name: 'accueil_user')]
    public function AfficheKmUser(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT km FROM App\Entity\User km");
        $resultat = $query->getResult();
        $vars = ['km' => $resultat];

        return $this->render('accueil/accueil_user.html.twig', $vars);
    }




    #[Route('/', name: 'home')]
    public function homeAfficheCours(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT cour FROM App\Entity\CourEau cour ORDER BY cour.distance ASC ");
        $resultat = $query->getResult();
        $vars = ['cours' => $resultat];
        //creer ici le bouton "voir"


        return $this->render('home/home.html.twig', $vars);
    }

    #[Route("/form/km/saisis", name: "form_km_saisis")]
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


    #[Route("/form/affiche/km")]
    public function formAfficheKm()
    {
        //faire ici addition des km?
        return $this->render("/accueil_user.html.twig");
    }

    #[Route("affiche/photo/{id}")]
    public function affichePhoto(Request $res)
    {
        //prendre ici les photos qui sont liés à l'id des CourEaux

        // prendre le cours d'eau de la bd find($id)
        // $cours->getPhoto() // ATTENTION CEST UNE COLLECTION
        // renvoyer les photos a la vue dans un JsonResponse


    }

    // ici je prend tous les cours pour les afficher dans le accordion2
    #[Route('/accueil/useraccordion', name: 'accordion')]
    public function accordionCours(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT cour FROM App\Entity\CourEau cour");
        $resultat = $query->getResult();


        // separer en valide et non validees
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
        dump($coursNonValidees);
        dd($coursValidees);

        $vars = [
            'coursValidees' => $coursValidees,
            'coursNonValidees' => $coursNonValidees
        ];



        return $this->render('accueil/accueil_user_accordion2.html.twig', $vars);
    }
}
