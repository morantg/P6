<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FigureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        $faker = \Faker\Factory::create('fr_FR');
        $user = new User();
        $user->setUsername('test')
             ->setEmail('test@gmail.com')
             ->setPassword('$2y$13$vtgqKAtTOc.A7RxKIuRc6uhcQrOcbJU0/X8kgTbEvVsT9LJYzSKN.');
             
        $manager->persist($user);
        
        $groupe = new Groupe();
        $groupe->setNom('mongroupe');

        $manager->persist($groupe);

        
        // Création de 10 figures
        for($i = 0; $i <= 10; $i++){
            $figure = new Figure();

            $content = '<p>';
            $content .= join($faker->paragraphs(15), '</p><p>');
            $content .= '</p>';

            $figure->setNom($faker->word(10))
                   ->setDescription($content)
                   ->setGroupe($groupe)
                   ->setAjoutAt($faker->dateTimeBetween('-6 months'))
                   ->setImageUne('http://via.placeholder.com/640x360')
                   ->setUser($user);

            $manager->persist($figure);
            
            // Création de 2 à 6 média
            for($j = 0; $j <= mt_rand(2,4); $j++){
                $media = new Media();
                $media->setFormat('jpg')
                      ->setUrl('http://via.placeholder.com/640x360')
                      ->setFigures($figure);
                      
                $manager->persist($media);      
            }
            

        }


        $manager->flush();
    }
}
