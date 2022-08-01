<?php

namespace App\Controller;

use App\Entity\Ilot;
use App\Form\IlotType;
use App\Repository\IlotRepository;
use App\Service\PreviousPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin", name="ilot_")
 */
class IlotController extends AbstractController
{
    /**
     * @Route("/ilots/index", name="index", methods={"GET", "POST"})
     */
    public function index(IlotRepository $ilotRepository, PreviousPage $previousPage): Response
    {
        return $this->render('ilot/index.html.twig', [
            'ilots' => $ilotRepository->findAll(),
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/ilot/{nomURL}", name="read", methods={"GET"})
     */
    public function read(PreviousPage $previousPage, IlotRepository $ilotRepository, Ilot $ilot = null): Response
    {
        // ParamConverter => si $ilot = null, alors notre contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        return $this->render('ilot/read.html.twig', [
            'ilot' => $ilot,
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/ilot/{nomURL}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em, PreviousPage $previousPage, Ilot $ilot = null): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $form = $this->createForm(IlotType::class, $ilot);
        $form->add('ilot', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'ilot-add btn-success'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'L\'îlot ' . $ilot->getNomIRL() . ' a bien été modifié.');

            return $this->redirectToRoute('ilot_read', ['nomURL' => $ilot->getNomURL()], 302);
        }

        return $this->render('ilot/edit.html.twig', [
            'ilot' => $ilot,
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente()
        ]);
    }

    /**
     * @Route("/ilots/add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $em, PreviousPage $previousPage): Response
    {
        $ilot = new Ilot();
        $form = $this->createForm(IlotType::class, $ilot);
        $form->add('ilot', SubmitType::class, [
            'label' => 'Ajouter',
            'attr' => [
                'class' => 'ilot-add btn-primary'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ilot);
            $em->flush();

            $this->addFlash('success', 'L\'îlot ' . $ilot->getNomIRL() . ' a bien été ajouté.');

            return $this->redirectToRoute('ilot_read', ['nomURL' => $ilot->getNomURL()], 302);
        }

        return $this->render('ilot/add.html.twig', [
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente()
        ]);
    }

    /**
     * @Route("ilot/{id<\d+>}/delete", name="delete", methods={"GET"})
     */
    public function delete(EntityManagerInterface $em, Ilot $ilot = null): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $em->remove($ilot);
        $em->flush();

        $this->addFlash('success', 'L\'îlot ' . $ilot->getNomIRL() . ' a bien été supprimé.');

        return $this->redirectToRoute('ilot_index');
    }
}