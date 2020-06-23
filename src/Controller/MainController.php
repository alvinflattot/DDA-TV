<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Page d'accueil du site
     * @Route("/", name="home")
     */
    public function index()
    {
        dump(5);
        // Cette page appellera la vue templates/main/index.html.twig
        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
