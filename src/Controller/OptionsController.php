<?php

namespace App\Controller;

use App\Entity\Badgeage;
use App\Entity\Ilot;
use App\Repository\BadgeageRepository;
use App\Repository\IlotRepository;
use App\Repository\OrdreFabRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/options/{nomURL}", name="options_")
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
     * @Route("/{historiqueIlot}", name="historique_ilot")
     */
    public function listOF(BadgeageRepository $badgeageRepository, Ilot $ilot = null, OrdreFabRepository $ordreFabRepository, IlotRepository $ilotRepository): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $ordreFab = $ordreFabRepository->findByDate();
        $badgeage = $badgeageRepository->findBy([
            'ordreFab' => $ordreFab, 'ilot' => $ilot
        ]);

        return $this->render('options/listOF.html.twig', [
            'badgeages' => $badgeage,
            'ilot' => $ilot
        ]);
    }
}
