<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Photo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PhotoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker= Factory::create();
        for ($i = 0; $i < 10 ; $i++){
            $photo= new Photo();
            $photo->setLien($faker->imageUrl($width = 640, $height = 480));
            
            $photo->setDescription($faker->text); 
            $manager->persist($photo);
        }
        // $product = new Product();
    

        $manager->flush();
    }
}
