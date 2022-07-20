<?php

namespace App\Controller;

use App\Entity\Badgeage;
use App\Entity\Client;
use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Form\OrdreFabType;
use App\Repository\BadgeageRepository;
use App\Repository\IlotRepository;
use App\Repository\OrdreFabRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="ilot_")
 */
class IlotController extends AbstractController
{
    /**
     * @Route("/{nomURL}", name="of", methods={"GET", "POST"})
     */
    // TODO : renommer
    public function getOF(): Response
    {
        return $this->render('view.html.twig', [

        ]);
    }

    /**
     * @Route("Peinture/{nomURL}", name="paint", methods={"GET"})
     */
    public function paint(Ilot $ilot = null): Response
    {
        if (null === $ilot) {
            throw $this->createNotFoundException('Ilot non trouvé.');
        }

        $form = $this->createForm(OrdreFabType::class, $ordreFab);
        $form->handleRequest($request);

        return $this->render('ilot/paint.html.twig', [
            'ilot' => $ilot,
            'form' => $form->createView()
        ]);
    }
}