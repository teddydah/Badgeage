<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IlotController extends AbstractController
{
    /**
     * @Route("/ilot", name="app_ilot")
     */
    public function index(): Response
    {
        return $this->render('ilot/index.html.twig', [
            'controller_name' => 'IlotController',
        ]);
    }
}
