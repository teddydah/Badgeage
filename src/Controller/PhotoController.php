<?php

namespace App\Controller;

use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use App\Repository\BadgeageRepository;
use App\Repository\IlotRepository;
use App\Repository\OrdreFabRepository;
use App\Service\FileUploader;
use App\Service\MessageGenerator;
use App\Service\PreviousPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        MessageGenerator   $messageGenerator,
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
                return $this->redirectToRoute('photo_select', [
                    'nomURL' => $ilot->getNomURL(),
                    'numero' => $badgeageExistant->getOrdreFab()->getNumero()
                ], 302);
            } else {
                $this->addFlash('danger', $messageGenerator->getMessageBadgeage($numOF, $ilot));
            }
        }

        return $this->render('photo/index.html.twig', [
            'ilot' => $ilot,
            'path' => $previousPage->pagePrecedente($ilotRepository),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{numero}/select", name="select", methods={"GET", "POST"})
     */
    public function selectPhoto(
        Request            $request,
        PreviousPage       $previousPage,
        IlotRepository     $ilotRepository,
        OrdreFabRepository $ordreFabRepository,
        BadgeageRepository $badgeageRepository,
        FileUploader       $fileUploader,
        MessageGenerator   $messageGenerator,
        Ilot               $ilot = null,
        OrdreFab           $ordreFab = null): Response
    {
        if (null === $ordreFab) {
            throw $this->createNotFoundException('OF non trouvé.');
        }

        $form = $this->createFormBuilder()
            ->add('photo', FileType::class, [
                'label' => '2. Sélectionner une photo',
                'constraints' => [
                    new NotBlank(['message' => 'Vous devez sélectionner une photo.']),
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Veuillez sélectionner une photo avec un format valide : jpg/jpeg, png, gif ou webp.'
                    ])
                ]
            ])
            ->add('suivant', SubmitType::class, [
                'label' => 'Suivant'
            ])
            ->getForm();
        $form->handleRequest($request);

        // Récupération du numéro d'OF qui est dans l'URL
        $numOF = $request->get('numero');

        $ordreFabExistant = $ordreFabRepository->findOneBy(["numero" => $numOF]);
        $badgeageExistant = $badgeageRepository->findOneBy([
            'ilot' => $ilot,
            'ordreFab' => $ordreFabExistant
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($badgeageExistant)) {
                $photo = $form->get('photo')->getData();

                if ($photo) {
                    $pictureFilename = $fileUploader->upload($photo, $ordreFab, $ilot);

                    $this->addFlash('picture', $pictureFilename);
                    $this->addFlash('success', 'La table SLF_PhotoUpload a été mise à jour.');

                    return $this->redirectToRoute('photo_index', ['nomURL' => $ilot->getNomURL()], 302);
                }
            } else {
                $this->addFlash('danger', $messageGenerator->getMessageBadgeage($numOF, $ilot));
            }
        }

        return $this->render('photo/index.html.twig', [
            'ilot' => $ilot,
            'path' => $previousPage->pagePrecedente($ilotRepository),
            'form' => $form->createView()
        ]);
    }
}
