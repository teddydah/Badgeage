<?php

namespace App\Controller;

use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Entity\Printer;
use App\Form\OrdreFabType;
use App\Service\PreviousPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{nomURL}/impression", name="impression_")
 */
class ImpressionController extends AbstractController
{
    /**
     * @Route(name="etiquette", methods={"GET", "POST"})
     */
    public function print(Request $request, PreviousPage $previousPage, Ilot $ilot = null, OrdreFab $ordreFab = null): Response
    {
        // ParamConverter => si $ilot est null, alors le contrôleur est exécuté
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->add('numero', null, [
            'label' => 'Impression étiquette'
        ]);
        $form->handleRequest($request);

        // TODO
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'L\'objet a bien été modifié.');
        }

        return $this->render('printer/print.html.twig', [
            'ilot' => $ilot,
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente()
        ]);
    }


}
