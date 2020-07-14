<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use App\Entity\Serie;

/**
 * Class CatalogueController
 * @package App\Controller
 * @Route("/catalogue", name="catalogue_")
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
        //récupération des 3 derniers films enregistrés en bdd
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $movieRepo->findLatestInsertedMovies();
        //récupération des séries type 'Mangas'
        $mangaRepo = $this->getDoctrine()->getRepository(Serie::class);
        $mangas = $mangaRepo->findByType('Mangas');

        //Appel de la vue en lui envoyant les séries, les films et les mangas
        return $this->render('catalogue/index.html.twig', [
            'controller_name' => 'CatalogueController',
            'series' => $series,
            'movies' => $movies,
            'mangas' => $mangas
        ]);

    }


    /**
     * Page des series
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

        // On stocke dans $pageMovies les 10 movies de la page demandée dans l'URL
        $pageSeries = $paginator->paginate(
            $query,     // Requête de selection des movies en BDD
            $requestedPage,     // Numéro de la page dont on veux les movies
            10      // Nombre d'movies par page
        );

        // Appel de la vue en lui envoyant les articles
        return $this->render('catalogue/serieList.html.twig', [
            'series' => $pageSeries
        ]);
    }


    /**
     * Page des movies
     * @Route("/movie/list", name="movie_list")
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
        //récupération des séries type 'Mangas'
        $mangaRepo = $this->getDoctrine()->getRepository(Serie::class);
        $mangas = $mangaRepo->findByType('Mangas');

        //Appel de la vue en lui envoyant les films
        return $this->render('catalogue/mangaList.html.twig', [
            'controller_name' => 'CatalogueController',
            'mangas' => $mangas
        ]);
    }
}
