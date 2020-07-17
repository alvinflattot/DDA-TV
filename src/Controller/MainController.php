<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Movie;
use App\Entity\User;


class MainController extends AbstractController
{
    /**
     * Page d'accueil du site
     * @Route("/", name="home")
     */
    public function home()
    {
        
        // Cette page appellera la vue templates/main/home.html.twig

        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * Test page 404
     * @Route("/404", name="err_404")
     */
    public function err_404()
    {
        //Page d'apel de la vue 404 pour test
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }
    


    /**
     * Page des parametre du profil
     *
     * @Route("/parametre/", name="my_setting")
     * @Security("is_granted('ROLE_USER')")
     */
    public function profilSettings()
    {
        return $this->render('main/setting.html.twig');
    }

    /**
     * Page du profil qui affichera la liste des films/series aimé
     * @Route("/profil", name="profil")
     * Security("is_granted('ROLE_USER')")
     */
    public function profil()
    {
        

        // On envoi les movies récupérés à la vue
        return $this->render('main/profil.html.twig');
    }

    /**
     * Page contacte
     * @Route("/contactez-nous", name="contact")
     */
    public function contact()
    {
        return $this->render('main/contact.html.twig');
    }


    /**
     * Page mentions légales
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function mentions()
    {
        return $this->render('main/mentionsLegales.html.twig');
    }
  
}
