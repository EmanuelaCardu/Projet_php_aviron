<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Photo;
use App\Entity\CourEau;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PhotoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker= Factory::create();

        //chercher tous les cours d'Eau
        $repCourEaux = $manager->getRepository(CourEau::class);
        $arrayObjCourEaux = $repCourEaux->findAll();

        foreach ($arrayObjCourEaux as $courEau) {
        for ($i = 0; $i < 2 ; $i++){
            $photo= new Photo();
            $photo->setLien($faker->imageUrl($width = 340, $height = 380));

            // $photo->setCourEau($arrayObjCourEaux[array_rand($arrayObjCourEaux)]);
            $photo->setCourEau($courEau);
            $photo->setDescription($faker->text); 
            $manager->persist($photo);
        }
        // $product = new Product();
        $manager->flush();
    }   
    }


    public function getDependencies(): array
    {
        return [
            CourEauFixtures::class,
            
        ];
    }
}
