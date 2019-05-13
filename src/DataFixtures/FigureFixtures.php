<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Entity\Figure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FigureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        $faker = \Faker\Factory::create('fr_FR');

        // Création de 10 figures
        for($i = 0; $i <= 10; $i++){
            $figure = new Figure();

            $content = '<p>';
            $content .= join($faker->paragraphs(15), '</p><p>');
            $content .= '</p>';

            $figure->setNom($faker->word(10))
                   ->setDescription($content)
                   ->setGroupe($faker->word(10))
                   ->setAjoutAt($faker->dateTimeBetween('-6 months'))
                   ->setImageUne($faker->imageUrl());

            $manager->persist($figure);
            
            // Création de 2 à 6 média
            for($j = 0; $j <= mt_rand(4,8); $j++){
                $media = new Media();
                $media->setFormat('jpg')
                      ->setUrl($faker->imageUrl())
                      ->setFigures($figure);
                      
                $manager->persist($media);      
            }
            

        }


        $manager->flush();
    }
}
