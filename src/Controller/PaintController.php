<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaintController extends AbstractController
{
    /**
     * @Route("/paint", name="app_paint")
     */
    public function index(): Response
    {
        return $this->render('paint.html.twig', [
            'controller_name' => 'PaintController',
        ]);
    }
}
