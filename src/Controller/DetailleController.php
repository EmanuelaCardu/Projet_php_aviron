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
        //on fixe l' id ,on recupere le cour d'eau lié à cet id et on le renvoye à detaille_cours_eau

        $id = $req->get('id');
        $CourEau = $rep->find($id);

        $vars = ['courEau' => $CourEau];
        return $this->render('coursEau/detaille_cours_eau.html.twig', $vars);
        }   

        #[Route('/coursEau/valider/', name: 'validerCour')]
        public function validerCourEau(Request $req, ManagerRegistry $doctrine, SerializerInterface $serializer): Response
        {
            
            $user = $this->getUser();
        
            $id = $req->get('id');
            $entityManager = $doctrine->getManager();
            $rep = $entityManager->getRepository(CourEau::class);
    
            $courEau = $rep->find($id);
        
            $rajoute = false;
            // si le km de l'user sont < que la distance de ce cour
            if($user->getKm()<$courEau->getDistance())
            {
                $rajoute =false;
            }
            else
            {   
                //enleve les km de la distance aux km de l user
                $user->setKm($user->getKm()-$courEau->getDistance());
                //rajoute le cour d' eau à l'user 
                $user->addCourEaux($courEau);
                $entityManager->flush();
            }
    
            
        
            return new JsonResponse(['rajoute' => $rajoute], 200);
        }

}




