<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends AbstractController
{
    /**
     * Page d'accueil du site
     * @Route("/", name="home")
     */
    public function home()
    {


        // Cette page appellera la vue templates/main/index.html.twig
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
     * Page de profil
     *
     * @Route("/mon-profil/", name="main_profil")
     * @Security("is_granted('ROLE_USER')")
     */
    public function profil()
    {
        return $this->render('main/profil.html.twig');
    }

    /**
     * Page contact
     * @Route("/contactez-nous", name="contact")
     */
    public function contact()
    {
        return $this->render('main/contact.html.twig');
    }



    
}
