<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Serie;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\User;
use App\Entity\Movie;
use \DateTime;
use Faker;


class AppFixtures extends Fixture
{
    private $encoder;

    /**
     * Utilisation du constructeur pour récupérer le service de hashage des mots de passe via autowiring
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // On instancie le Faker en langue française
        $faker = Faker\Factory::create('fr_FR');

        /////Les série/////////////////////////////////////////////////////
        for($i = 1; $i <= 5; $i++){

            $newSerie = new Serie();
     
            $newSerie 
                 ->setTitle( $faker->sentence ) // Phrase aléatoire
                 ->setSummary( $faker->sentence)
                 ->setType( $faker->word)
                 ->setposter( 'a.png' )
                 ->setStartYear( $faker->dateTimeBetween('-20years', 'now') )   // Date aléatoire entre maintenant et il y a 20 ans
            ;
     
            // Enregistrement du nouvel film auprès de Doctrine
            $manager->persist($newSerie);

        }

        //////ces saisons ///////////////////////////////////////////////////
        for($i = 1; $i <= 20; $i++){

            $newSeason = new Season();
    
            $newSeason 
                ->setSeasonNumber( $faker->numberBetween($min = 1, $max = 12) ) // Phrase aléatoire
                ->setSerie($newSerie)
                ->setSummary( $faker->sentence)
            ;
    
            // Enregistrement du nouvel film auprès de Doctrine
            $manager->persist($newSeason);

        }
        
        //////ces épisodes /////////////////////////////////////////////////
        for($i = 1; $i <= 100; $i++){

            $newEpisode = new Episode;
    
            $newEpisode 
                    ->setEpisodeNumber( $faker->numberBetween($min = 1, $max = 50)) // Phrase aléatoire
                    ->setTitle( $faker->sentence ) // Phrase aléatoire
                    ->setSummary( $faker->sentence)
                    ->setDuration( $faker->numberBetween($min = 30, $max = 7200))
                    ->setReleaseDate( $faker->dateTimeBetween('-20years', 'now') )  // Date aléatoire entre maintenant et il y a 20 ans
                    ->setSeason($newSeason)
                ;
    
            // Enregistrement du nouvel film auprès de Doctrine
            $manager->persist($newEpisode);

        }

        //////  Les films  /////////////////////////////////////////////////
        for($i = 1; $i <= 25; $i++){

            // Création d'un nouvel film////
            $newMovie = new Movie();
            

            // Hydratation du nouvel film
            $dir = '../../public/images';
            $newMovie
                ->setTitle( $faker->sentence ) // Phrase aléatoire
                ->setSummary( $faker->sentence)
                ->setType( $faker->word)
                ->setposter( $faker->imageUrl($width = 640, $height = 480) )
                ->setDuration( $faker->numberBetween($min = 30, $max = 97200))
                ->setReleaseDate( $faker->dateTimeBetween('-20years', 'now') )   // Date aléatoire entre maintenant et il y a 20 ans
            ;
            
            // Enregistrement du nouvel film auprès de Doctrine
            $manager->persist($newMovie);

        }

        //// Les utilisateurs /////////////////////////////////////////
        for($i = 0; $i < 10; $i++){

            // Création d'un nouveau compte
            $user = new User();

            // Hydratation du compte avec des données aléatoire
            $user
                ->setFirstname( $faker->userName )
                ->setLastname( $faker->userName )
                ->setEmail( $faker->email )
                ->setPassword( $this->encoder->encodePassword($user, 'aaaaaaaaA7/') )
                ->setRegistrationDate( $faker->dateTimeBetween('-1 year', 'now') )
                ->setActivated( $faker->boolean )
                ->setActivationToken( $faker->md5 )
            ;

            // Persistance du compte
            $manager->persist($user);
 
        }

        $manager->flush();
    }
}
