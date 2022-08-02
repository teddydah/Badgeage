<?php

namespace App\Controller;

use App\Entity\Printer;
use App\Form\PrinterType;
use App\Repository\IlotRepository;
use App\Repository\PrinterRepository;
use App\Service\PreviousPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin", name="printer_")
 */
class PrinterController extends AbstractController
{
    /**
     * @Route("/printers/index", name="index", methods={"GET", "POST"})
     */
    public function index(PrinterRepository $printerRepository, IlotRepository $ilotRepository, PreviousPage $previousPage): Response
    {
        return $this->render('printer/index.html.twig', [
            'printers' => $printerRepository->findAll(),
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/printer/{id<\d+>}", name="read", methods={"GET"})
     */
    public function read(PreviousPage $previousPage, IlotRepository $ilotRepository, Printer $printer = null): Response
    {
        // ParamConverter => si $printer = null, alors notre contrôleur est exécuté
        if (null === $printer) {
            throw $this->createNotFoundException('Imprimante non trouvée.');
        }

        return $this->render('printer/read.html.twig', [
            'printer' => $printer,
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/printer/{id<\d+>}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(
        Request                $request,
        EntityManagerInterface $em,
        PreviousPage           $previousPage,
        IlotRepository         $ilotRepository,
        Printer                $printer = null): Response
    {
        if (null === $printer) {
            throw $this->createNotFoundException('Imprimante non trouvée.');
        }

        $form = $this->createForm(PrinterType::class, $printer);
        $form->add('printer', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'printer-add btn-success'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'L\'imprimante ' . $printer->getNom() . ' a bien été modifiée.');

            return $this->redirectToRoute('printer_read', ['id' => $printer->getId()], 302);
        }

        return $this->render('printer/edit.html.twig', [
            'printer' => $printer,
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/printers/add", name="add", methods={"GET", "POST"})
     */
    public function add(
        Request                $request,
        EntityManagerInterface $em,
        PreviousPage           $previousPage,
        IlotRepository         $ilotRepository): Response
    {
        $printer = new Printer();
        $form = $this->createForm(PrinterType::class, $printer);
        $form->add('printer', SubmitType::class, [
            'label' => 'Ajouter',
            'attr' => [
                'class' => 'printer-add btn-primary'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($printer);
            $em->flush();

            $this->addFlash('success', 'L\'imprimante ' . $printer->getNom() . ' a bien été ajoutée.');

            return $this->redirectToRoute('printer_read', ['id' => $printer->getId()], 302);
        }

        return $this->render('printer/add.html.twig', [
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/printer/{id<\d+>}/delete", name="delete", methods={"GET"})
     */
    public function delete(IlotRepository $ilotRepository, EntityManagerInterface $em, Printer $printer = null): Response
    {
        if (null === $printer) {
            throw $this->createNotFoundException('Imprimante non trouvée.');
        }

        $ilots = $ilotRepository->findOneBy(['printer' => $printer]);

        if (!isset($ilots)) {
            $em->remove($printer);
            $em->flush();

            $this->addFlash('success', 'L\'imprimante ' . $printer->getNom() . ' a bien été supprimée.');
        } else {
            $this->addFlash('danger', 'Impossible de supprimer l\'imprimante ' . $printer->getNom() . '. Elle est déjà rattachée à un ou plusieurs îlot(s).');
        }
        return $this->redirectToRoute('printer_index');
    }
}
