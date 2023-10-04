<?php

namespace App\Controller;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/accueil/user', name: 'accueil_user')]
    public function accueilUser(): Response
    {



        return $this->render('accueil/accueil_user.html.twig');
    }
    //faire select pour prendre la liste de la bd



    #[Route('/', name: 'home')]
    public function homeAfficheCours(ManagerRegistry $doctrine){ 

        $em= $doctrine->getManager(); 
        $query = $em->createQuery ("SELECT cour FROM App\Entity\CourEau cour ORDER BY cour.distance ASC ");
        $resultat= $query->getResult();
        $vars = ['cours' => $resultat];




       
       
        //     $query = $em->createQuery ("SELECT livre.titre, livre.prix FROM App\Entity\Livre livre ".
        //                             "WHERE livre.prix>15");
        //     $resultat = $query->getResult();
        //     $vars = ['livres'=> $resultat];
        //     return $this->render ("exemples_dql/exemple_select_array_arrays.html.twig", $vars);
        // }



        return $this->render('home/home.html.twig',$vars);
    }
}
