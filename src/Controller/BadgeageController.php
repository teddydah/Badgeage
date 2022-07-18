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
     * @Route("/{nomURL}/view", name="detail")
     */
    public function detail(
        Ilot                   $ilot = null,
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
        $this->add($ilot, $ilotRepository, $ordreFabRepository, $badgeageRepository, $request, $em);

        return $this->render('badgeage/detail.html.twig', [
            'ilot' => $ilot,
            'sousIlots' => $ilotRepository->findBySousIlotsPeinture(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="edit")
     */
    public function edit(): Response
    {
        return $this->render('badgeage/index.html.twig', [

        ]);
    }

    /**
     * @Route("/add", name="add")
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

            if ($badgeage !== null) {
                // TODO : Màj la date
                // TODO : redirectToRoute("mettre date à jour")
                $this->addFlash('danger', $ilot->getNomIRL() . ' : l\'OF ' . $numOF . ' a déjà été badgé.');
            } else {
                $this->addOF($badge, $ordre, $ilot);
                $em->persist($badge);
                $em->flush();

                $this->addFlash('success', $ilot->getNomIRL() . ' : commande ' . $badge->getOrdreFab()->getNumero() . ' validée.');
            }
        }

        return $this->redirectToRoute('badgeage_detail', ['nomURL' => $ilot->getNomURL()]);
    }

    /**
     * @Route("/{id<\d+>}/delete", name="delete")
     */
    public function delete(): Response
    {
        return $this->render('badgeage/index.html.twig', [
            'controller_name' => 'BadgeageController',
        ]);
    }

    private function addOF(Badgeage $badge, OrdreFab $ordreFab, Ilot $ilot)
    {
        date_default_timezone_set('Europe/Paris');

        $badge->setOrdreFab($ordreFab);
        $badge->setIlot($ilot);
        $badge->setDateBadgeage(new \DateTime());

        return $badge;
    }
}
