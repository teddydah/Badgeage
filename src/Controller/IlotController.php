<?php

namespace App\Controller;

use App\Entity\Badgeage;
use App\Entity\Client;
use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use App\Repository\BadgeageRepository;
use App\Repository\OrdreFabRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{nomURL}", name="ilot_")
 */
class IlotController extends AbstractController
{
    /**
     * @Route(name="of", methods={"GET", "POST"})
     */
    // TODO : renommer méthode
    public function getOF(
        Ilot                   $ilot = null,
        Client                 $client = null,
        Badgeage               $badgeage = null,
        OrdreFab               $ordreFab = null,
        OrdreFabRepository     $ordreFabRepository,
        BadgeageRepository     $badgeageRepository,
        Request                $request,
        EntityManagerInterface $em): Response
    {
        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        date_default_timezone_set('Europe/Paris');

        //$badgeage = new Badgeage();
        $ordre = new OrdreFab();
//
//        if (!is_null($client)) {
//            $ordre->setClient($client); // TODO
//        } else {
//            $repo = $this->getDoctrine()->getRepository(OrdreFab::class);
//            $ordre = $repo->findByNumero($noOrdreFab);
//            // if $ordre null = interroge API
//            // if $ordre toujours null = msg erreur
//            $client = $ordre->getClient()[0];
//        }
//        $ordre->setDateEcheance($dt->add(new \DateInterval('P10D')));


        //if ($form->isSubmitted() && $form->isValid()) {

        /*$noOrdreFab = $form->get("numero")->getData();
        $ordre = $this->getDoctrine()
            ->getRepository(OrdreFab::class)
            ->findOneByNumero($noOrdreFab);

        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findOneById($ordre->getClient());*/

        /*$badgeage = $this->getDoctrine()
            ->getRepository(Badgeage::class)
            ->findByIlotByOrdre($ordre->getNumero());

        if(!is_null($badgeage)) {
            // todo: proposer de modifier la date
        }*/

        // check si deja badge
        // si oui redirect vers la meme page


//            if (!is_null($client)) {
//                $ordre->setClient($client); // TODO
//            } else {
//                $repo = $this->getDoctrine()->getRepository(OrdreFab::class);
//                $ordre = $repo->findByNumero($noOrdreFab);
//                // if $ordre null = interroge API
//                // if $ordre toujours null = msg erreur
//                $client = $ordre->getClient();
//            }
        //}

        $form = $this->createForm(OrdreFabType::class, $ordre);
        $form->handleRequest($request);

        $ordreFab = $ordreFabRepository->findAll();

        foreach ($ordreFab as $fab) {
            // Si un numéro d'OF de la table OrdreFab correspond au numéro d'OF saisi
            if ($fab->getNumero() == $form->get('numero')->getData()) {
                if ($badgeage->getOrdreFab()->getNumero() !== $form->get('numero')->getData()) {
                    $badgeage->setIlot($ilot);
                    $badgeage->setOrdreFab($fab);
                    $badgeage->setDateBadgeage(new \DateTime());
                    $fab->addBadgeage($badgeage);

                    // Si le formulaire est soumis et valide
                    if ($form->isSubmitted() && $form->isValid()) {
                        $em->persist($badgeage);
                        $em->flush();

                        $this->addFlash('success', $ilot->getNomIRL() . ' : commande ' . $badgeage->getOrdreFab()->getNumero() . ' validée.');
                    }
                } else {
                    // TODO
                    $this->addFlash('danger', $ilot->getNomIRL() . ' : l\'OF ' . $form->get('numero')->getData() . ' a déjà été badgé.');
                }
            }
        }

        return $this->render('ilot/read.html.twig', ['ilot' => $ilot,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/options", name="options", methods={"GET"})
     */
    public
    function options(Ilot $ilot = null)
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        return $this->render('ilot/options.html.twig', [
            'ilot' => $ilot
        ]);
    }
}