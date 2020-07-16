<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Movie;
use App\Entity\Serie;
use App\Form\SerieType;

/**
 * Class CatalogueController
 * @package App\Controller
 * @Route("/catalogue", name="catalogue_")
 * Toutes les pages commenceront leur url par "/catalogue" et leur nom par "catalogue_"
 */
class CatalogueController extends AbstractController
{
    /**
     * Page du catalogue
     * @Route("/", name="index")
     */
    public function index()
    {
        //Récupération des 3 dernières séries enregistrées en bdd
        $serieRepo = $this->getDoctrine()->getRepository(Serie::class);
        $series = $serieRepo->findLatestInsertedSeries();
        //Récupération des 3 derniers films enregistrées en bdd
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $movieRepo->findLatestInsertedMovies();
        //Récupération des 3 derniers mangas ( = série type mangas)
        $mangaRepo = $this->getDoctrine()->getRepository(Serie::class);
        $mangas = $mangaRepo->findByType('Mangas');

        //appel de la vue en lui envoyant les séries, les films et les mangas récupérés
        return $this->render('catalogue/index.html.twig', [
            'controller_name' => 'CatalogueController',
            'series' => $series,
            'movies' => $movies,
            'mangas' => $mangas
        ]);
    }

    /**
     * Page des séries
     * @Route("/serie/list", name="serie_list")
     */
    public function serieList(Request $request, PaginatorInterface $paginator)
    {
        // On récupère dans l'url la données GET page (si elle n'existe pas, la valeur retournée par défaut sera la page 1)
        $requestedPage = $request->query->getInt('page', 1);

        // Si le numéro de page demandé dans l'url est inférieur à 1, erreur 404
        if($requestedPage < 1){
            throw new NotFoundHttpException();
        }

        // Récupération du manager des entités
        $em = $this->getDoctrine()->getManager();

        // Création d'une requête qui servira au paginator pour récupérer les articles de la page courante
        $query = $em->createQuery('SELECT a FROM App\Entity\Serie a');

        // On stocke dans $pageSeries les 10 movies de la page demandée dans l'URL
        $pageSeries = $paginator->paginate(
            $query,     // Requête de selection des series en BDD
            $requestedPage,     // Numéro de la page dont on veux les series
            10      // Nombre d'series par page
        );

        // On envoi les movies récupérés à la vue
        return $this->render('catalogue/serieList.html.twig', [
            'series' => $pageSeries
        ]);
    }

    /**
     * Page des films
     * @Route("/movie/list", name="movie_list")
     * @Security("is_granted('ROLE_USER')")
     */
     public function movieList(Request $request, PaginatorInterface $paginator)
     {
         // On récupère dans l'url la données GET page (si elle n'existe pas, la valeur retournée par défaut sera la page 1)
         $requestedPage = $request->query->getInt('page', 1);

         // Si le numéro de page demandé dans l'url est inférieur à 1, erreur 404
         if($requestedPage < 1){
             throw new NotFoundHttpException();
         }

         // Récupération du manager des entités
         $em = $this->getDoctrine()->getManager();

         // Création d'une requête qui servira au paginator pour récupérer les articles de la page courante
         $query = $em->createQuery('SELECT a FROM App\Entity\Movie a');

         // On stocke dans $pageMovies les 10 movies de la page demandée dans l'URL
         $pageMovies = $paginator->paginate(
             $query,     // Requête de selection des movies en BDD
             $requestedPage,     // Numéro de la page dont on veux les movies
             10      // Nombre d'movies par page
         );

         // On envoi les movies récupérés à la vue
         return $this->render('catalogue/movieList.html.twig', [
             'movies' => $pageMovies
         ]);
     }

    /**
     * Page des mangas
     * @Route("/manga/list", name="manga_list")
     */
    public function mangaList()
    {
        //récupération des séries de types 'mangas'
        $mangaRepo = $this->getDoctrine()->getRepository(Serie::class);
        $mangas = $mangaRepo->findByType('Mangas');

        //Appel de la vue en lui envoyant les manga
        return $this->render('catalogue/mangaList.html.twig', [
            'controller_name' => 'CatalogueController',
            'mangas' => $mangas
        ]);
    }



    /**
     * Page vue série seul
     * @Route("/view/{slug}", name="serie_view", requirements={"id"="\d+"})
     */
    public function serieView(Serie $serie){
        return $this->render('catalogue/serieView.html.twig', [
            'controller_name' => 'CatalogueController',
            'serie' => $serie
        ]);
    }


    // Gestion des Films ///////////////////////////////////////////////////////////////////
    /**
     * Page pour aouter un film à sa liste de favoris
     * 
     * @Route("/film/ajouter-aux-favoris/{id}", name="add_movie_to_favorite" )
     *
     */
    public function addFavoriteMovie(Movie $movie)
    {
        $connectedUser = $this->getUser();        

        $connectedUser->addFavoriteMovie($movie);



        // Récupération du manager des entités de Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Message flash de succès
        $this->addFlash('success', 'Vous avez bien ajouté ce film à vos favoris');

        // Redirection de l'utilisateur sur sa page profil 
        return $this->redirectToRoute('profil');
    }

    /**
     * Page pour supprimer un film de la liste des favoris
     * 
     * @Route("/film/supprimer-des-favoris/{id}", name="remove_movie_to_favorite" )
     *
     */
    public function removeFavoriteMovie(Movie $movie)
    {
        $connectedUser = $this->getUser();        

        $connectedUser->removeFavoriteMovie($movie);



        // Récupération du manager des entités de Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Message flash de succès
        $this->addFlash('success', 'Vous avez bien supprimé ce film de vos favoris');

        // Redirection de l'utilisateur sur sa page profil 
        return $this->redirectToRoute('profil');
    }
    
    /**
     * Page pour marquer un film comme 'vu'
     * 
     * @Route("/film/ajouter-aux-vues/{id}", name="add_movie_to_watched" )
     *
     */
    public function addWatchedMovie(Movie $movie)
    {
        $connectedUser = $this->getUser();        

        $connectedUser->addMoviesWatched($movie);



        // Récupération du manager des entités de Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Message flash de succès
        $this->addFlash('success', 'Vous avez bien ajouté ce film à vos vues');

        // Redirection de l'utilisateur sur sa page profil 
        return $this->redirectToRoute('profil');
    }

    /**
     * Page pour marquer un film comme non 'vu'
     * 
     * @Route("/film/supprimer-des-vues/{id}", name="remove_movie_to_watched" )
     *
     */
    public function removeWatchedMovie(Movie $movie)
    {
        $connectedUser = $this->getUser();        

        $connectedUser->removeMoviesWatched($movie);



        // Récupération du manager des entités de Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Message flash de succès
        $this->addFlash('success', 'Vous avez bien supprimé ce film de vos vues');

        // Redirection de l'utilisateur sur sa page profil 
        return $this->redirectToRoute('profil');
    }

    // Gestion des Séries ///////////////////////////////////////////////////////////////////
     /**
     * Page pour aouter un serie à sa liste de favoris
     * 
     * @Route("/serie/ajouter-aux-favoris/{id}", name="add_serie_to_favorite" )
     *
     */
    public function addFavoriteSerie(Serie $serie)
    {
        $connectedUser = $this->getUser();        

        $connectedUser->addFavoriteSerie($serie);



        // Récupération du manager des entités de Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Message flash de succès
        $this->addFlash('success', 'Vous avez bien ajouté cette serie de vos favoris');

        // Redirection de l'utilisateur sur sa page profil 
        return $this->redirectToRoute('profil');
    }

    /**
     * Page pour supprimer un serie de la liste des favoris
     * 
     * @Route("/serie/supprimer-des-favoris/{id}", name="remove_serie_to_favorite" )
     *
     */
    public function removeFavoriteSerie(Serie $serie)
    {
        $connectedUser = $this->getUser();        

        $connectedUser->removeFavoriteSeries($serie);



        // Récupération du manager des entités de Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Message flash de succès
        $this->addFlash('success', 'Vous avez bien supprimé cette serie de vos favoris');

        // Redirection de l'utilisateur sur sa page profil 
        return $this->redirectToRoute('profil');
    }
    
    

    

}
