<?php

namespace App\Controller;

use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IlotController extends AbstractController
{
    /**
     * @Route("/{nomURL}", name="ilot_detail", methods={"GET"})
     */
    public function detail(Ilot $ilot = null, OrdreFab $ordreFab = null, Request $request): Response
    {
        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->handleRequest($request);

        return $this->render('ilot/detail.html.twig', [
            'ilot' => $ilot,
            'form' => $form->createView()
        ]);
    }
}