<?php

namespace App\Controller;

use App\Entity\Badgeage;
use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use App\Repository\BadgeageRepository;
use App\Repository\IlotRepository;
use App\Repository\OrdreFabRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{nomURL}/options", name="options_")
 */
class OptionsController extends AbstractController
{
    /**
     * @Route("/menu", name="menu", methods={"GET"})
     */
    public function menu(Ilot $ilot = null, Badgeage $badgeage = null): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        return $this->render('options/menu.html.twig', [
            'ilot' => $ilot,
            'badgeage' => $badgeage
        ]);
    }

    /**
     * @Route("/HistoriqueIlot", name="historique_ilot", methods={"GET"})
     */
    public function listOF(BadgeageRepository $badgeageRepository, Ilot $ilot = null): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        return $this->render('options/listOF.html.twig', [
            'badgeages' => $badgeageRepository->findBadgeageByIlot($ilot),
            'ilot' => $ilot
        ]);
    }

    /**
     * @Route("/HistoriqueCommande", name="historique_commande", methods={"GET", "POST"})
     */
    public function getOF(
        BadgeageRepository $badgeageRepository,
        OrdreFabRepository $ordreFabRepository,
        Request            $request,
        Ilot               $ilot = null): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $ordreFab = new OrdreFab();

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Historique OF ',
            'attr' => [
                'placeholder' => 'Veuillez badger un OF',
                'autofocus' => true
            ]
        ]);
        $form->handleRequest($request);

        $numOF = $form->get('numero')->getData();

        $ordreFabExistant = $ordreFabRepository->findOneBy(["numero" => $numOF]);
        $badgeageExistant = $badgeageRepository->findOneBy(['ordreFab' => $ordreFabExistant]);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($badgeageExistant)) {
                return $this->redirectToRoute('options_historique_commande_of', [
                    'numero' => $badgeageExistant->getOrdreFab()->getNumero(),
                    'nomURL' => $ilot->getNomURL()
                ], 302);
            } else {
                $this->addFlash('danger', 'Pas de badgeage pour la commande ' . $numOF);
            }
        }

        return $this->render('options/getOF.html.twig', [
            'badgeages' => $badgeageRepository->findBy(
                ['ordreFab' => $ordreFabExistant],
                ['dateBadgeage' => 'DESC']
            ),
            'badgeage' => $badgeageExistant,
            'ilot' => $ilot,
            'numOF' => $numOF,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/HistoriqueCommande/{numero}", name="historique_commande_of", methods={"GET", "POST"})
     */
    public function listIlots(
        BadgeageRepository $badgeageRepository,
        OrdreFabRepository $ordreFabRepository,
        Request            $request,
        Ilot               $ilot = null
    ): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $ordreFab = new OrdreFab();

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Historique OF ',
            'attr' => [
                'placeholder' => 'Veuillez badger un OF',
                'autofocus' => true
            ]
        ]);
        $form->handleRequest($request);

        $numOF = $form->get('numero')->getData();
        if (null !== $numOF) {
            $numOF == $form->get('numero')->getData();
        } else {
            $numOF = $request->get('numero');
        }

        $ordreFabExistant = $ordreFabRepository->findOneBy(["numero" => $numOF]);
        $badgeageExistant = $badgeageRepository->findOneBy(['ordreFab' => $ordreFabExistant]);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!isset($badgeageExistant)) {
                $this->addFlash('danger', 'Pas de badgeage pour la commande ' . $numOF);
            }
        }

        return $this->render('options/listIlots.html.twig', [
            'badgeages' => $badgeageRepository->findBy(
                ['ordreFab' => $ordreFabExistant],
                ['dateBadgeage' => 'DESC']
            ),
            'badgeage' => $numOF,
            'ilot' => $ilot,
            'numOF' => $numOF,
            'form' => $form->createView()
        ]);
    }
}
