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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/photo/{nomURL}", name="photo_")
 */
class PhotoController extends AbstractController
{
    /**
     * @Route(name="index", methods={"GET", "POST"})
     */
    public function index(
        Request            $request,
        PreviousPage       $previousPage,
        OrdreFabRepository $ordreFabRepository,
        BadgeageRepository $badgeageRepository,
        IlotRepository     $ilotRepository,
        Ilot               $ilot = null): Response
    {
        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $ordreFab = new OrdreFab();
        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => '1. Badger l\'OF correspondant au colis',
            'attr' => [
                'autofocus' => true
            ]
        ]);
        $form->handleRequest($request);

        // Récupération du numéro d'OF depuis le formulaire
        $numOF = $form->get('numero')->getData();

        // Récupération de l'OF avec $numOF depuis la BDD
        $ordreFabExistant = $ordreFabRepository->findOneBy(["numero" => $numOF]);

        $badgeageExistant = $badgeageRepository->findOneBy([
            "ilot" => $ilot,
            "ordreFab" => $ordreFabExistant
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($badgeageExistant)) {
                return $this->redirectToRoute('photo_select', ['nomURL' => $ilot->getNomURL()], 302);
            } else {
                $this->addFlash('danger', $ilot->getNomIRL() . ' : l\'OF ' . $numOF . ' n\'existe pas.');
            }
        }

        return $this->render('photo/index.html.twig', [
            'ilot' => $ilot,
            'path' => $previousPage->pagePrecedente($ilotRepository),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/select", name="select", methods={"GET", "POST"})
     */
    public function selectPhoto(PreviousPage $previousPage, IlotRepository $ilotRepository, Ilot $ilot = null): Response
    {
        $form = $this->createFormBuilder()
            ->add('photo', FileType::class, [
                'label' => '2. Choisir une photo'
            ])
            ->getForm();

//        if ($form->isSubmitted() && $form->isValid()) {
//            return $this->redirectToRoute('photo_file', ['nomURL' => $ilot->getNomURL()], 302);
//        }

        return $this->render('photo/index.html.twig', [
            'ilot' => $ilot,
            'path' => $previousPage->pagePrecedente($ilotRepository),
            'form' => $form->createView()
        ]);
    }
}
