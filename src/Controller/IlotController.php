<?php

namespace App\Controller;

use App\Entity\Badgeage;
use App\Entity\Client;
use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use App\Repository\OrdreFabRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IlotController extends AbstractController
{
    /**
     * @Route("/{nomURL}", name="ilot_detail", methods={"GET", "POST"})
     */
    // TODO : renommer méthode
    public function detail(Ilot $ilot = null, OrdreFab $of = null, Badgeage $badgeage = null, Request $request, EntityManagerInterface $em): Response
    {
        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $form = $this->createForm(OrdreFabType::class, $of);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('numero')->getData() == $badgeage->getOrdreFab()->getNumero()) {
                $this->addFlash('success', $ilot->getNomIRL() . ' : commande ' . $badgeage->getOrdreFab()->getNumero() . ' validée.');
            } else {
                $this->addFlash('danger', $ilot->getNomIRL() . ' : l\'OF ' . $form->get('numero')->getData() . ' n\'existe pas.');
            }

            return $this->redirectToRoute('ilot_detail', [
                'nomURL' => $ilot->getNomURL()
            ]);
        }

        return $this->render('ilot/detail.html.twig', [
            'ilot' => $ilot,
            'form' => $form->createView()
        ]);
    }
}