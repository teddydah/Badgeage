<?php

namespace App\Controller;

use App\Repository\IlotRepository;
use App\Service\PreviousPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route(name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/settings", name="settings", methods={"GET"})
     */
    public function settings(PreviousPage $previousPage, IlotRepository $ilotRepository): Response
    {
        return $this->render('admin/settings.html.twig', [
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }
}
