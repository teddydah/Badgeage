<?php

namespace App\Controller;

use App\Repository\IlotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="main_")
 */
class MainController extends AbstractController
{
    /**
     * @Route(name="home", methods={"GET"})
     */
    public function home(IlotRepository $ilotRepository): Response
    {
        return $this->render('main/home.html.twig', [
            'ilots' => $ilotRepository->findAll()
        ]);
    }
}
