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

        $badge = new Badgeage();
        $ordreFab = new OrdreFab();


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


        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->handleRequest($request);

        // Recup ilot depuis url
        //$ilot = $this->getDoctrine()
          //  ->getRepository(Ilot::class)
            //->findOneBy(["nomURL" => $request->get('nomURL')]);

        // Si pas d'ilot dans MYSQL alors 404
        //if (null === $ilot) {
          //  throw $this->createNotFoundException('Ilot non trouvé.');
        //}


        if ($form->isSubmitted() && $form->isValid()) {
            // $ordreFab = $ordreFabRepository->findAll();
            // $badgeageRepo = $badgeageRepository->findAll();

//            // Recup ilot depuis url
//            $ilot_url = $request->get('ilot')->getData();
//            $ilot = $this->getDoctrine()
//                ->getRepository(Ilot::class)
//                ->findOneBy(["nomURL" => $ilot_url]);
//
//            // Si pas d'ilot dans MYSQL alors 404
//            if (null === $ilot) {
//                throw $this->createNotFoundException('Ilot non trouvé.');
//            }

            // Récupération du numéro d'OF depuis le formulaire
            $numOF = $form->get('numero')->getData();

            // Récupération de l'OF avec $numOF depuis la BDD
            $ordre = $ordreFabRepository->findOneBy(["numero" => $numOF]);

            // TODO : Ajouter : si OF existant null alors interroger API + màj MYSQL avec nouvel OF (peu probable)
            $badgeageOF = $badgeageRepository->findOneBy(["ordreFab" => $ordre]);
            $badgeageIlot = $badgeageRepository->findOneBy(["ilot" => $ilot]);

            if ($badgeageOF !== null && $badgeageIlot !== null) {
                // todo: on update la date
                // todo: redirecttoRoute("mettre date à jour")
                $this->addFlash('danger', $ilot->getNomIRL() . ' : l\'OF ' . $numOF . ' a déjà été badgé. Mettre à jour la date ? O/N');
                // throw new \Error("Mettre à jour la date ? Y/N");
            } else {
                $this->addOF($badge, $ordre, $ilot);
                $em->persist($badge);
                $em->flush();

                $this->addFlash('success', $ilot->getNomIRL() . ' : commande ' . $badge->getOrdreFab()->getNumero() . ' validée.');
            }
        }

        return $this->render('ilot/read.html.twig', ['ilot' => $ilot,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/options", name="options", methods={"GET"})
     */
    public function options(Ilot $ilot = null)
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        return $this->render('ilot/options.html.twig', [
            'ilot' => $ilot
        ]);
    }

    private function addOF(Badgeage $badge, OrdreFab $ordreFab, Ilot $ilot)
    {
        $badge->setOrdreFab($ordreFab);
        $badge->setIlot($ilot);
        $badge->setDateBadgeage(new \DateTime());

        return $badge;
    }


}