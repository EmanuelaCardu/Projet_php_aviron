<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\CourEau;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture; 

class CourEauFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 10 ; $i++){
            $courEau = new CourEau();
            $courEau->setNom ("courEau".$i);

            $courEau->setDescription($faker->text());
            $courEau->setDistance(20 +$i);
            $manager->persist ($courEau);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
} 


