<?php

namespace App\Controller;

use App\Repository\IlotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route(name="index")
     */
    public function index(IlotRepository $ilotRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'ilots' => $ilotRepository->findAll()
        ]);
    }
}
