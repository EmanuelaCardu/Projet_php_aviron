<?php

namespace App\DataFixtures;


use DateTime;
use App\Entity\Photo;
use App\Entity\CourEau;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;


class PhotoFixtures extends Fixture
{

        
    private $em;
    private string $dossierImport;

    // on doit injecter le dossier qui contient le fichier à importer
    // (voir services.yaml, ligne "bind")
    // on doit aussi injecter le Manager pour manipuler la BD
    public function __construct ($dossierImport, EntityManagerInterface $em){
        $this->em = $em;
        $this->dossierImport = $dossierImport;
    }
        
    

    // méthode qui réalise l'importation, elle reçoit le chemin du fichier complet
    public function load(ObjectManager $manager): void
    {



        $arrayCsv = array_map("str_getcsv", file($this->dossierImport . DIRECTORY_SEPARATOR . "photo.csv"));

        $repoCoursEau = $manager->getRepository(CourEau::class);
    
        
        //POUR MOI que les en-têtes
        unset ($arrayCsv[0]);
        // unset ($arrayCsv[1]);

        // pour chaque autre ligne on crée une entité et on la stocke dans la BD
        foreach ($arrayCsv as $ligneCsv){
            $qi = new Photo ();
            // $qi->setProposedBy($ligneCsv[1]);
            
            // fixer le cours d'eau (objet)
            $qi->setCourEau($repoCoursEau->find($ligneCsv[1]));

            $qi->setLien($ligneCsv[2]);
            $qi->setDescription($ligneCsv[3]);
        
        

            $this->em->persist ($qi);
        
        }
        
        $this->em->flush();
    }
}
