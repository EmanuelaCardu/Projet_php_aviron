<?php

namespace App\Controller;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        return $this->render('home/home.html.twig', $vars);
    }

    #[Route("/form/km/saisis", name: "form_km_saisis")]
    public function recupKmForm(Request $req)
    {
        
        $km = $req->request->get('km');
   
        return $this->render(
            "accueil/accueil_user.html.twig",
            ['km' => $km]
        );
    }


    #[Route("/form/affiche/km")]
    public function FormAfficheKm()
    {
        //faire ici addition des km?
        return $this->render("/accueil_user.html.twig");
    }
}
