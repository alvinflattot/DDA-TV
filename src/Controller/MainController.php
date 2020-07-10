<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends AbstractController
{
    /**
     * Page d'accueil du site
     * @Route("/", name="home")
     */
    public function index()
    {

        // Cette page appellera la vue templates/main/index.html.twig
        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * Page d'accueil du site
     * @Route("/home2", name="home_d")
     */
    public function indexDeDorian()
    {

        // Cette page appellera la vue templates/main/index.html.twig
        return $this->render('main/home-dorian.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * Page du catalogue
     * @Route("/catalogue", name="catalogue")
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
        return $this->render('main/catalogue.html.twig', [
            'movies' => $pageMovies
        ]);
    }



    
}
