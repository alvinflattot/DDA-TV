<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MainController extends AbstractController
{
    /**
     * Page d'accueil du site
     * @Route("/", name="home")
     */
    public function home()
    {
        dump(5);
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


}
