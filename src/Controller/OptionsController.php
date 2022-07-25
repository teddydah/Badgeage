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
 * @Route("/options", name="options_")
 */
class OptionsController extends AbstractController
{
    /**
     * @Route("/{nomURL}/menu", name="menu", methods={"GET"})
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
     * @Route("/{nomURL}/HistoriqueIlot", name="historique_ilot", methods={"GET"})
     */
    public function listOF(BadgeageRepository $badgeageRepository, Ilot $ilot = null, Badgeage $badgeage = null): Response
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
     * @Route("/{nomURL}/HistoriqueCommande", name="historique_commande", methods={"GET", "POST"})
     */
    public function detailCommande(BadgeageRepository $badgeageRepository, OrdreFabRepository $ordreFabRepository, Request $request, Ilot $ilot = null): Response
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

        // Récupération du numéro d'OF depuis le formulaire
        $numOF = $form->get('numero')->getData();

        $ordreFabExistant = $ordreFabRepository->findOneBy(["numero" => $numOF]);
        $badgeageExistant = $badgeageRepository->findOneBy(['ordreFab' => $ordreFabExistant]);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!isset($badgeageExistant)) {
                $this->addFlash('danger', 'Pas de badgeage pour la commande ' . $numOF);
            }
        }

        return $this->render('options/detailCommande.html.twig', [
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
}
