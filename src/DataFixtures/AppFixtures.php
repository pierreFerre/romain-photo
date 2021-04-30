<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Biography;
use App\Entity\Portfolio;
use App\Entity\Photography;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    const NB_PORTFOLIOS = 3;
    const NB_PHOTOS = 10 * self::NB_PORTFOLIOS;

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // PORTFOLIOS
        // Array to store the portfolios
        $portfolioList = [];
        for ($i = 1; $i <= self::NB_PORTFOLIOS; $i++) {
            $portfolio = new Portfolio();
            $portfolio->setName('Portfolio' . $i);
            $portfolio->setCreatedAt(new \Datetime());
            $portfolioList[] = $portfolio;

            $manager->persist($portfolio);
        }

        // PHOTOGRAPHIES
        // Directory containing the files
        $pictureDir = '/var/www/html/romain-photo/public/images';  
        // Array containing the free pictures for testing
        $pictures = scandir($pictureDir);
        // extracting the unwanted files from the list
        $unwanted1 = '.';
        $unwanted2 = '..';
        // If the value correspond to the the unwanted data, we unset it from the array
        foreach ($pictures as $picture => $value) {
            if ($value == $unwanted1 || $value == $unwanted2) {
                unset($pictures[$picture]);                
            }
        }

        // Array to store the photographies
        $photographyList = [];

        for ($p = 1; $p <= self::NB_PHOTOS; $p++) {
            $photography = new Photography();
            $photography->setTitle($faker->sentence());

            $randomPortfolio = $portfolioList[mt_rand(0, count($portfolioList) - 1)];
            $photography->setPortfolio($randomPortfolio);
            $photography->setCreatedAt(new \Datetime());

            $pictureKey = array_rand($pictures);
            $pictureName = 'images/' . $pictures[$pictureKey];
            $photography->setPicture($pictureName);

            $photographyList[] = $photography;

            $manager->persist($photography);
        }

        $biography = new Biography();
        $biography->setDescription($faker->paragraphs(2, true));
        $manager->persist($biography);

        $manager->flush();
    }
}
