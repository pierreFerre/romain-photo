<?php

namespace App\DataFixtures;

use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    const NB_COLECTIONS = 3;
    const NB_PHOTOS = 10 * self::NB_COLECTIONS;

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $manager->flush();
    }
}
