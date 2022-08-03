<?php

namespace App\Controller;

use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use App\Repository\BadgeageRepository;
use App\Repository\IlotRepository;
use App\Repository\OrdreFabRepository;
use App\Service\PreviousPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/impression/{nomURL}", name="impression_")
 */
class ImpressionController extends AbstractController
{
    /**
     * @Route(name="print", methods={"GET", "POST"})
     */
    public function print(
        OrdreFabRepository $ordreFabRepository,
        BadgeageRepository $badgeageRepository,
        IlotRepository     $ilotRepository,
        Request            $request,
        PreviousPage       $previousPage,
        Ilot               $ilot = null,
        OrdreFab           $ordreFab = null): Response
    {
        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Code-barres ' . $ilot->getNomIRL(),
            'attr' => [
                'placeholder' => 'Scannez OF',
                'autofocus' => true
            ]
        ]);
        $form->handleRequest($request);

        $imprimante = "Désactivée";
        // $imprimante = "Active";

        switch ($imprimante) {
            case 'Active' :
                $isActive = 'isActive';
                $imprimante = 'Active';
                break;
            default:
                $isActive = "isNotActive";
            // $imprimante = 'Désactivée';
        }

        $badgeageExistant = null;

        // TODO
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du numéro d'OF depuis le formulaire
            $numOF = $form->get('numero')->getData();

            // Récupération de l'OF avec $numOF depuis la BDD
            $ordreFabExistant = $ordreFabRepository->findOneBy(["numero" => $numOF]);

            $badgeageExistant = $badgeageRepository->findOneBy([
                "ilot" => $ilot,
                "ordreFab" => $ordreFabExistant
            ]);

            if (isset($badgeageExistant)) {
                $this->addFlash('success', 'Impression réussie pour ' . $badgeageExistant->getOrdreFab()->getNumero() . '.');
            } else {
                $this->addFlash('danger', $ilot->getNomIRL() . ' : l\'OF ' . $numOF . ' n\'existe pas.');
            }
        }

        return $this->render('impression/print.html.twig', [
            'ilot' => $ilot,
            'badgeage' => $badgeageExistant,
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente($ilotRepository),
            'isActive' => $isActive,
            'imprimante' => $imprimante
        ]);
    }
}
