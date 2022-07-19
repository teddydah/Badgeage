<?php

namespace App\Controller;

use App\Entity\Badgeage;
use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use App\Repository\BadgeageRepository;
use App\Repository\IlotRepository;
use App\Repository\OrdreFabRepository;
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
        Ilot                   $ilot = null,
        Badgeage               $badgeage = null,
        IlotRepository         $ilotRepository,
        OrdreFabRepository     $ordreFabRepository,
        BadgeageRepository     $badgeageRepository,
        Request                $request,
        EntityManagerInterface $em): Response
    {
        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $ordreFab = new OrdreFab();
        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Badgeage OF ' . $ilot->getNomIRL(),
            'attr' => ['placeholder' => 'Scannez OF']
        ]);
        $this->add($ilot, $ilotRepository, $ordreFabRepository, $badgeageRepository, $request, $em);

        return $this->render('badgeage/view.html.twig', [
            'ilot' => $ilot,
            'sousIlots' => $ilotRepository->findBySousIlotsPeinture(),
            'form' => $form->createView(),
            'badgeage' => $badgeage
        ]);
    }

    /**
     * @Route("/Peinture/{nomURL}/view", name="view_paint", methods={"GET", "POST"})
     */
    public function viewPaint(
        Ilot                   $ilot = null,
        IlotRepository         $ilotRepository,
        OrdreFabRepository     $ordreFabRepository,
        BadgeageRepository     $badgeageRepository,
        Request                $request,
        EntityManagerInterface $em): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $ordreFab = new OrdreFab();
        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Badgeage OF ' . $ilot->getNomIRL(),
            'attr' => ['placeholder' => 'Scannez OF']
        ]);
        $this->add($ilot, $ilotRepository, $ordreFabRepository, $badgeageRepository, $request, $em);

        return $this->render('badgeage/view.html.twig', [
            'ilot' => $ilot,
            'sousIlots' => $ilotRepository->findBySousIlotsPeinture(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(
        Ilot                   $ilot = null,
        IlotRepository         $ilotRepository,
        OrdreFabRepository     $ordreFabRepository,
        BadgeageRepository     $badgeageRepository,
        Request                $request,
        EntityManagerInterface $em): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $badge = new Badgeage();
        $ordreFab = new OrdreFab();

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du numéro d'OF depuis le formulaire
            $numOF = $form->get('numero')->getData();

            // Récupération de l'OF avec $numOF depuis la BDD
            $ordre = $ordreFabRepository->findOneBy(["numero" => $numOF]);

            // TODO : Ajouter : si OF existant null alors interroger API + màj MYSQL avec nouvel OF (peu probable)
            $badgeage = $badgeageRepository->findOneBy([
                "ilot" => $ilot,
                "ordreFab" => $ordre
            ]);

            if (isset($ordre)) {
                if ($badgeage !== null) {
                    $this->addFlash('warning', $ilot->getNomIRL() . ' : l\'OF ' . $numOF . ' a déjà été badgé.');
                    // TODO ?
                    $msg = '<a href="edit/' . $badgeage->getId() . '" title="Mettre à jour la date de badgeage de l\'OF">Voulez-vous mettre à jour la date ?</a>';

                    $this->addFlash('custom', $msg);
                } else {
                    // Appel de la méthode addBadgeage()
                    $this->addBadgeage($badge, $ordre, $ilot);
                    $em->persist($badge);
                    $em->flush();

                    $this->addFlash('success', $ilot->getNomIRL() . ' : commande ' . $badge->getOrdreFab()->getNumero() . ' validée.');
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
    public function edit(Ilot $ilot = null, Badgeage $badgeage = null, BadgeageRepository $badgeageRepository, OrdreFabRepository $ordreFabRepository, Request $request, EntityManagerInterface $em): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $ordreFab = new OrdreFab();

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $numOF = $form->get('numero')->getData();

        // ordre = $ordreFabRepository->findByNumeroOF();
        $ordre = $ordreFabRepository->findOneBy(["numero" => $numOF]);
        $badge = $badgeageRepository->findBy(['id' => $badgeage->getId()]);

        date_default_timezone_set('Europe/Paris');

        $badgeage->setDateBadgeage(new \DateTime());
        $em->flush();

        $msg = "<div class='msg'>
                    <span>Mise à jour de la date effectuée.</span>
                    <span>Nouvelle date : <strong>" . $badgeage->getDateBadgeage()->format("d/m/Y") . "</strong></span>
                    <span>OF : <strong>" . $badgeage->getOrdreFab()->getNumero() . "</strong></span>
                    <span>Ilot : <strong>" . $ilot->getNomIRL() . "</strong></span>
                </div>";

        $this->addFlash('msg', $msg);

        return $this->redirectToRoute('badgeage_view', ['nomURL' => $ilot->getNomURL()], 302);
    }

    /**
     * @Route("/{nomURL}/detail", name="detail", methods={"GET", "POST"})
     */
    public function detail(Ilot $ilot = null, Badgeage $badgeage = null, Request $request, EntityManagerInterface $em): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $ordreFab = new OrdreFab();

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Code-barres'
        ]);
        $form->handleRequest($request);

        $numOF = $form->get('numero')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($badgeage) && $badgeage->getOrdreFab()->getNumero() === $numOF) {
                return $this->redirectToRoute('badgeage_delete', ['nomURL' => $ilot->getNomURL()], 302);
            } else {
                $this->addFlash('danger', 'Le badgeage ' . $numOF . ' pour l\'îlot ' . $ilot->getNomIRL() . ' n\'existe pas.');
            }
        }

        return $this->render('badgeage/detail.html.twig', ['ilot' => $ilot,
            'badgeage' => $badgeage,
            'numOF' => $form->get('numero')->getData(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{nomURL}/delete", name="delete", methods={"GET", "POST"})
     */
    public function delete(Ilot $ilot = null, Badgeage $badgeage = null, Request $request, EntityManagerInterface $em): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $ordreFab = new OrdreFab();

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Code-barres',
            'data' => $badgeage->getOrdreFab()->getNumero()
        ]);
        $form->handleRequest($request);

        $numOF = $form->get('numero')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($badgeage->getOrdreFab()->getNumero() === $numOF) {
                $em->remove($badgeage);
                $em->flush();

                $this->addFlash('success', 'Le badgeage ' . $badgeage->getOrdreFab()->getNumero() . ' pour l\'îlot ' . $ilot->getNomIRL() . ' a bien été supprimé.');

                return $this->redirectToRoute('badgeage_detail', ['nomURL' => $ilot->getNomURL()], 302);
            } else {
                $this->addFlash('danger', 'Le badgeage ' . $numOF . ' pour l\'îlot ' . $ilot->getNomIRL() . ' n\'existe pas.');
            }
        }

        return $this->render('badgeage/delete.html.twig', ['ilot' => $ilot,
            'badgeage' => $badgeage,
            'numOF' => $form->get('numero')->getData(),
            'form' => $form->createView()
        ]);
    }

    private function addBadgeage(Badgeage $badge, OrdreFab $ordreFab, Ilot $ilot)
    {
        date_default_timezone_set('Europe/Paris');

        $badge->setOrdreFab($ordreFab);
        $badge->setIlot($ilot);
        $badge->setDateBadgeage(new \DateTime());
        // TODO
        $badge->setRecid(1);

        return $badge;
    }
}
