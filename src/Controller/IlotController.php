<?php

namespace App\Controller;

use App\Entity\Ilot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ilot", name="ilot_")
 */
class IlotController extends AbstractController
{
    /**
     * @Route("/{nomAX<\d+>}", name="detail", methods={"GET"})
     */
    public function detail(Ilot $ilot = null): Response
    {
        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        return $this->render('ilot/detail.html.twig', [
            'ilot' => $ilot
        ]);
    }
}