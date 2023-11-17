<?php

namespace App\DataFixtures;



use DateTime;
use App\Entity\CourEau;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;


class CourEauFixtures extends Fixture
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


        
        $arrayCsv = array_map("str_getcsv", file($this->dossierImport . DIRECTORY_SEPARATOR . "cour_eau.csv"));

   
        //pour moi que les en-têtes
        unset ($arrayCsv[0]);
        
        foreach ($arrayCsv as $ligneCsv){
            $qi = new CourEau ();
            
            // $qi->setProposedBy($ligneCsv[1]);
            $qi->setNom($ligneCsv[1]);
            $qi->setDescription($ligneCsv[2]);
            $qi->setDistance($ligneCsv[3]);
            $this->em->persist ($qi);
            // dd($qi); // pour debugger avant de stocker...
        }
    
        $this->em->flush();
    }
}
