<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('catalogue/index.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }

    /**
     * Page vue seul (test vue kaamelott)
     * @Route("/view", name="view")
     */
    public function view(){
        return $this->render('catalogue/view.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }
}
