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

    //Création nouveau faker pour type de séries 'mangas'
    private static $serieTypes = [
        'Feuilleton',
        'Mangas'
    ];

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
                 ->setType( $faker->randomElement(self::$serieTypes))
                 ->setposter( 'img/jaquette'.$faker->numberBetween($min = 1, $max = 10).'.png' )
                 ->setStartYear( $faker->dateTimeBetween('-20years', 'now') )   // Date aléatoire entre maintenant et il y a 20 ans
            ;
     
            // Enregistrement du nouvel film auprès de Doctrine
            $manager->persist($newSerie);

            $series[] = $newSerie;

        }

        $rickMorty = new Serie();
     
        $rickMorty 
             ->setTitle("Rick et Morty") 
             ->setSummary("Rick est un scientifique âgé et déséquilibré qui a récemment renoué avec sa famille. Il passe le plus clair de son temps à entraîner son petit-fils Morty dans des aventures extraordinaires et dangereuses, à travers l'espace et dans des univers parallèles.")
             ->setType( 'animation')
             ->setposter( 'img/jaquette'.$faker->numberBetween($min = 1, $max = 10).'.png' )
             ->setStartYear( $faker->dateTimeBetween('-20years', 'now') ) 
        ;
 
        // Enregistrement du nouvel film auprès de Doctrine
        $manager->persist($newSerie);


        //////ces saisons ///////
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
        
        //////ces épisodes ///////
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
                ->setposter( 'img/jaquette'.$faker->numberBetween($min = 1, $max = 10).'.png' )
                ->setDuration( $faker->numberBetween($min = 30, $max = 97200))
                ->setReleaseDate( $faker->dateTimeBetween('-20years', 'now') )   // Date aléatoire entre maintenant et il y a 20 ans
            ;
            
            // Enregistrement du nouvel film auprès de Doctrine
            $manager->persist($newMovie);

            $movies[] = $newMovie;

        }

        // Création d'un nouvel film////
        $totoro = new Movie();
            

        // Hydratation du nouvel film
        $dir = '../../public/images';
        $totoro
            ->setTitle( 'Totoro' ) // Phrase aléatoire
            ->setSummary( 'Deux petites filles, Mei, 4 ans, et Satsuki, 10 ans, s\'installent dans une grande maison à la campagne avec leur père pour se rapprocher de l\'hôpital où séjourne leur mère. Elles découvrent la nature tout autour de la maison et, surtout, l\'existence de créatures merveilleuses, les Totoros, avec qui elles deviennent très amies.')
            ->setType( 'animation')
            ->setposter( 'img/jaquette4.png' )
            ->setDuration( 86*60)
            ->setReleaseDate( new DateTime('1999-09-08') )   // Date aléatoire entre maintenant et il y a 20 ans
        ;
        
        // Enregistrement du nouvel film auprès de Doctrine
        $manager->persist($totoro);

        // Création compte admin///////////////////////////////////////
        $admin = new User();

        // Hydratation du compte
        $admin
            ->setFirstname( 'bob' )
            ->setLastname( 'lepatron' )
            ->setEmail('admin@a.a')
            ->setRegistrationDate( $faker->dateTimeBetween('-1 year', 'now') )
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->encoder->encodePassword($admin, 'Aaaaaa1+'))
            ->setActivated(true)    // Compte activé
            ->setActivationToken(md5( random_bytes(100) ))
            ->addFavoriteMovie($faker->randomElement($movies))
            ->addFavoriteMovie($faker->randomElement($movies))
            ->addFavoriteMovie($faker->randomElement($movies))
            ->addFavoriteSeries($faker->randomElement($series))
            ->addFavoriteSeries($faker->randomElement($series))
        ;

        // Persistance du compte
        $manager->persist($admin);

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
