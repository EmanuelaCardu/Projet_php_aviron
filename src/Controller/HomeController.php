<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(): Response
    {
        //faire select pour prendre la liste de la bd
        return $this->render('accueil/accueil.html.twig');
    }

    #[Route('/home', name: 'home')]
    public function index2(): Response
    {
        return $this->render('home/home.html.twig');
    }
}
