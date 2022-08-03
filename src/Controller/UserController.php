<?php

namespace App\Controller;

use App\Repository\IlotRepository;
use App\Service\PreviousPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user/{id<\d+>}", name="read", methods={"GET"})
     */
    public function read(PreviousPage $previousPage, IlotRepository $ilotRepository): Response
    {
        return $this->render('user/read.html.twig', [
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }
}
