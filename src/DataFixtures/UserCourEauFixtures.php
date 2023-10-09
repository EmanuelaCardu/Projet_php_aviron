<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\CourEau;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserCourEauFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        // 1. Obtenir tous les User
        $repUsers = $manager->getRepository(User::class);
        $arrayObjUsers = $repUsers->findAll();

        // 2. Obtenir tous les CourEau
        $repCourEaux = $manager->getRepository(CourEau::class);
        $arrayObjCourEaux = $repCourEaux->findAll();

        // 3. Parcourir tous les Users. Pour chaque User, rajouter (add) un Cour aléatoire
        foreach ($arrayObjUsers as $User) {
            $randomIndex = mt_rand(0, count($arrayObjCourEaux) - 1); // l'index d'un CourEau, random
            $User->addCourEaux($arrayObjCourEaux[$randomIndex]); 
            $manager->persist($User);
        }
        $manager->flush();
    }

    // fixer les dépéndances de cette fixture
    public function getDependencies(): array
    {
        return [
            CourEauFixtures::class,
            UserFixtures::class
        ];
    }
}