<?php

namespace App\Controller;

use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use App\Repository\IlotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="paint_")
 */
class PaintController extends AbstractController
{
    /**
     * @Route("/badgeage/{nomURL}/index", name="index", methods={"GET"})
     */
    public function index(IlotRepository $ilotRepository, Ilot $ilot = null): Response
    {

        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        // Récupération des sous-îlots "Etiquettes Laquage : OF" et "Etiquettes Laquage : RAL" propres au sous-îlot "Etiquettes Laquage"
        $sousIlotsLaquageURL = [
            'LaqEtiqOF',
            'LaqEtiqRAL'
        ];

        return $this->render('paint/index.html.twig', [
            'ilot' => $ilot,
            'sousIlots' => $ilotRepository->findBySousIlotsPeinture(),
            'laqEtiqHome' => $ilotRepository->findOneBy(['nomURL' => 'laqEtiqHome']),
            'sousIlotsLaquage' => $ilotRepository->findBy(['nomURL' => $sousIlotsLaquageURL])
        ]);
    }

    /**
     * @Route("/peinture/{nomURL}/view", name="view", methods={"GET", "POST"})
     */
    public function view(IlotRepository $ilotRepository, Ilot $ilot = null): Response
    {

        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        // Récupération des sous-îlots "Etiquettes Laquage : OF" et "Etiquettes Laquage : RAL" propres au sous-îlot "Etiquettes Laquage"
        $sousIlotsLaquageURL = [
            'LaqEtiqOF',
            'LaqEtiqRAL'
        ];

        $ordreFab = new OrdreFab();
        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Badgeage OF ' . $ilot->getNomIRL(),
            'attr' => [
                'placeholder' => 'Scannez OF',
                'autofocus' => true
            ]
        ]);

        return $this->render('paint/view.html.twig', [
            'ilot' => $ilot,
            'sousIlots' => $ilotRepository->findBySousIlotsPeinture(),
            'laqEtiqHome' => $ilotRepository->findOneBy(['nomURL' => 'LaqEtiqHome']),
            'sousIlotsLaquage' => $ilotRepository->findBy(['nomURL' => $sousIlotsLaquageURL]),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("peinture/Laquage/{nomURL}", name="laquage", methods={"GET", "POST"})
     */
    public function laquage(IlotRepository $ilotRepository, Ilot $ilot = null): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        // Récupération des sous-îlots "Etiquettes Laquage : OF" et "Etiquettes Laquage : RAL" propres au sous-îlot "Etiquettes Laquage"
        $sousIlotsLaquageURL = [
            'LaqEtiqOF',
            'LaqEtiqRAL'
        ];

        $ordreFab = new OrdreFab();
        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Badgeage OF ' . $ilot->getNomIRL(),
            'attr' => [
                'placeholder' => 'Scannez OF',
                'autofocus' => true
            ]
        ]);

        return $this->render('paint/laquage.html.twig', [
            'ilot' => $ilot,
            'sousIlots' => $ilotRepository->findBySousIlotsPeinture(),
            'sousIlotsLaquage' => $ilotRepository->findBy(
                ['nomURL' => $sousIlotsLaquageURL],
                ['nomURL' => 'ASC']
            ),
            'form' => $form->createView()
        ]);
    }
}
