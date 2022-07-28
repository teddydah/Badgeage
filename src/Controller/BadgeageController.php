<?php

namespace App\Controller;

use App\Entity\Badgeage;
use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use App\Repository\BadgeageRepository;
use App\Repository\IlotRepository;
use App\Repository\OrdreFabRepository;
use App\Service\PreviousPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/badgeage", name="badgeage_")
 */
class BadgeageController extends AbstractController
{
    /**
     * @Route("/{nomURL}/view", name="view", methods={"GET", "POST"})
     */
    public function view(
        IlotRepository         $ilotRepository,
        OrdreFabRepository     $ordreFabRepository,
        BadgeageRepository     $badgeageRepository,
        Request                $request,
        EntityManagerInterface $em,
        PreviousPage           $previousPage,
        Ilot                   $ilot = null,
        Badgeage               $badgeage = null): Response
    {
        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        // Redirection vers "impression/nomURL/view" si le nomURL est "MiseEnFab", "LaqEtiqOF" ou "LaqEtiqRAL"
        if ($ilot->getNomURL() == "MiseEnFab" || $ilot->getNomURL() == "LaqEtiqOF" || $ilot->getNomURL() == "LaqEtiqRAL") {
            return $this->redirectToRoute('impression_view', ['nomURL' => $ilot->getNomURL()], 301);
        }

        // Redirection vers "badgeage/Laquage/LaqEtiqHome" si le nomURL est "LaqEtiqHome"
        if ($ilot->getNomURL() == "LaqEtiqHome") {
            return $this->redirectToRoute('badgeage_laquage', ['nomURL' => $ilot->getNomURL()], 301);
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

        $this->add($ordreFabRepository, $badgeageRepository, $request, $em, $ilot);

        return $this->render('badgeage/view.html.twig', [
            'ilot' => $ilot,
            'badgeage' => $badgeage,
            'sousIlots' => $ilotRepository->findBySousIlotsPeinture(),
            'laqEtiqHome' => $ilotRepository->findOneBy(['nomURL' => 'laqEtiqHome']),
            'sousIlotsLaquage' => $ilotRepository->findBy(['nomURL' => $sousIlotsLaquageURL]),
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente()
        ]);
    }

    /**
     * @Route("/Laquage/{nomURL}", name="laquage", methods={"GET", "POST"})
     */
    public function laquage(IlotRepository $ilotRepository, PreviousPage $previousPage, Ilot $ilot = null): Response
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

        return $this->render('badgeage/laquage.html.twig', [
            'ilot' => $ilot,
            'sousIlots' => $ilotRepository->findBySousIlotsPeinture(),
            'sousIlotsLaquage' => $ilotRepository->findBy(
                ['nomURL' => $sousIlotsLaquageURL],
                ['nomURL' => 'ASC']
            ),
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente()
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(
        OrdreFabRepository     $ordreFabRepository,
        BadgeageRepository     $badgeageRepository,
        Request                $request,
        EntityManagerInterface $em,
        Ilot                   $ilot = null): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $badgeage = new Badgeage();
        $ordreFab = new OrdreFab();

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du numéro d'OF depuis le formulaire
            $numOF = $form->get('numero')->getData();

            // Récupération de l'OF avec $numOF depuis la BDD
            $ordreFabExistant = $ordreFabRepository->findOneBy(["numero" => $numOF]);

            // Si OF existant null alors interroger API + màj MYSQL avec nouvel OF (peu probable)
//            if (null === $ordreFabExistant) {
//                $api = new ApiController();
//                $ordreFabExistant = $api->getOneOrdreByNumero($numOF);
//
//                if (null === $ordreFabExistant) {
//                    throw $this->createNotFoundException('OF non trouvé.');
//                }
//            }

            $badgeageExistant = $badgeageRepository->findOneBy([
                "ilot" => $ilot,
                "ordreFab" => $ordreFabExistant
            ]);

            if (isset($ordreFabExistant)) {
                if ($badgeageExistant !== null) {
                    $this->addFlash('warning', $ilot->getNomIRL() . ' : l\'OF ' . $numOF . ' a déjà été badgé.');

                    $this->addFlash('custom', "edit/" . $badgeageExistant->getId());
                } else {
                    // Appel de la méthode addBadgeage()
                    $this->addBadgeage($badgeage, $ordreFabExistant, $ilot);
                    $em->persist($badgeage);
                    $em->flush();

                    $this->addFlash('success', $ilot->getNomIRL() . ' : commande ' . $badgeage->getOrdreFab()->getNumero() . ' validée.');
                }
            } else {
                $this->addFlash('danger', $ilot->getNomIRL() . ' : l\'OF ' . $numOF . ' n\'existe pas.');
            }
        }
        return $this->redirectToRoute('badgeage_view', ['nomURL' => $ilot->getNomURL()], 302);
    }

    /**
     * @Route("/{nomURL}/edit/{id<\d+>}", name="edit", methods={"GET", "POST"})
     */
    public function edit(EntityManagerInterface $em, Badgeage $badgeage = null): Response
    {
        if (null === $badgeage) {
            throw $this->createNotFoundException('Badgeage non trouvé.');
        }

        date_default_timezone_set('Europe/Paris');

        $badgeage->setDateBadgeage(new \DateTime());
        $em->flush();

        $this->addFlash('msg', $badgeage);

        return $this->redirectToRoute('badgeage_view', ['nomURL' => $badgeage->getIlot()->getNomURL()], 302);
    }

    /**
     * @Route("/{nomURL}/detail", name="detail", methods={"GET", "POST"})
     */
    public function detail(
        OrdreFabRepository $ordreFabRepository,
        BadgeageRepository $badgeageRepository,
        Request            $request,
        PreviousPage       $previousPage,
        Ilot               $ilot = null,
        Badgeage           $badgeage = null): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        // Redirection vers la page d'accueil si le nomURL est "MiseEnFab", "LaqEtiqHome", "LaqEtiqOF" ou "LaqEtiqRAL"
        if (
            $ilot->getNomURL() == "MiseEnFab" ||
            $ilot->getNomURL() == "LaqEtiqHome" ||
            $ilot->getNomURL() == "LaqEtiqOF" ||
            $ilot->getNomURL() == "LaqEtiqRAL") {
            return $this->redirectToRoute('main_home', [], 301);
        }

        $ordreFab = new OrdreFab();

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Code-barres',
            'attr' => [
                'autofocus' => true
            ]
        ]);
        $form->handleRequest($request);

        $numOF = $form->get('numero')->getData();

        $ordreFabExistant = $ordreFabRepository->findOneBy(["numero" => $numOF]);
        $badgeageExistant = $badgeageRepository->findOneBy([
            "ilot" => $ilot,
            "ordreFab" => $ordreFabExistant
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($badgeageExistant) && $badgeageExistant->getOrdreFab()->getNumero() === $numOF) {
                return $this->redirectToRoute('badgeage_delete', [
                    'nomURL' => $ilot->getNomURL(),
                    'id' => $badgeageExistant->getId()
                ], 302);
            } else {
                $this->addFlash('danger', 'Le badgeage ' . $numOF . ' pour l\'îlot ' . $ilot->getNomIRL() . ' n\'existe pas.');
            }
        }

        return $this->render('badgeage/detail.html.twig', [
            'ilot' => $ilot,
            'badgeage' => $badgeage,
            'numOF' => $form->get('numero')->getData(),
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente()
        ]);
    }

    /**
     * @Route("/{nomURL}/delete/{id<\d+>}", name="delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, EntityManagerInterface $em, PreviousPage $previousPage, Badgeage $badgeage = null): Response
    {
        if (null === $badgeage) {
            throw $this->createNotFoundException('Badgeage non trouvé.');
        }

        $ordreFab = new OrdreFab();

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Code-barres',
            'data' => $badgeage->getOrdreFab()->getNumero(),
            'attr' => [
                'disabled' => true
            ]
        ]);
        $form->handleRequest($request);

        $numOF = $form->get('numero')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($badgeage->getOrdreFab()->getNumero() !== null) {
                $em->remove($badgeage);
                $em->flush();

                $this->addFlash('success', 'Le badgeage ' . $badgeage->getOrdreFab()->getNumero() . ' pour l\'îlot ' . $badgeage->getIlot()->getNomIRL() . ' a bien été supprimé.');

                return $this->redirectToRoute('badgeage_detail', ['nomURL' => $badgeage->getIlot()->getNomURL()], 302);
            } else {
                $this->addFlash('danger', 'Le badgeage ' . $numOF . ' pour l\'îlot ' . $badgeage->getIlot()->getNomIRL() . ' n\'existe pas.');
            }
        }

        return $this->render('badgeage/delete.html.twig', [
            'ilot' => $badgeage->getIlot(),
            'badgeage' => $badgeage,
            'numOF' => $form->get('numero')->getData(),
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente()
        ]);
    }

    private function addBadgeage(Badgeage $badgeage, OrdreFab $ordreFab, Ilot $ilot): Badgeage
    {
        date_default_timezone_set('Europe/Paris');

        $badgeage->setOrdreFab($ordreFab);
        $badgeage->setIlot($ilot);
        $badgeage->setDateBadgeage(new \DateTime());
        // TODO
        $badgeage->setRecid(1);

        return $badgeage;
    }
}
