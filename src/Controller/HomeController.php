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
        $user->setKm ($user->getKm() + $km);
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['km'=> $user->getKm()]);
  
    }


    #[Route("/form/affiche/km")]
    public function formAfficheKm()
    {
        //faire ici addition des km?
        return $this->render("/accueil_user.html.twig");
    }

    #[Route ("affiche/photo/{id}")]
    public function affichePhoto(Request $res){
        //prendre ici les photos qui sont liés à l'id des CourEaux
    }

    // ici je prend tous les cours pour les afficher dans le accordion2
    #[Route('/accueil/useraccordion', name: 'accordion')]
    public function accordionCours(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT cour FROM App\Entity\CourEau cour  ");
        $resultat = $query->getResult();
        $vars = ['AccCours' => $resultat];
        
        

        return $this->render('accueil/accueil_user_accordion2.html.twig', $vars);
    }


}
