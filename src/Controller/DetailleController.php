<?php

namespace App\Controller;


use App\Entity\CourEau;
use App\Repository\CourEauRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class DetailleController extends AbstractController
{
    #[Route('/detailles/{id}', name: 'detaille_cours_eau')]
 
        public function AfficheDetaileCoursEau(Request $req, CourEauRepository $rep)

    {
     

        $id = $req->get('id');
        $CourEau = $rep->find($id);

        $vars = ['courEau' => $CourEau];
        return $this->render('coursEau/detaille_cours_eau.html.twig', $vars);
        }   
}




