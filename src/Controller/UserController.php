<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\IlotRepository;
use App\Repository\UserRepository;
use App\Service\PreviousPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin", name="user_")
 */
class UserController extends AbstractController
{
//    /**
//     * @Route("/users/index", name="index", methods={"GET"})
//     */
//    public function index(UserRepository $userRepository, IlotRepository $ilotRepository, PreviousPage $previousPage): Response
//    {
//        return $this->render('user/index.html.twig', [
//            'users' => $userRepository->findAll(),
//            'path' => $previousPage->pagePrecedente($ilotRepository)
//        ]);
//    }

    /**
     * @Route("/user/{id<\d+>}", name="read", methods={"GET"})
     */
    public function read(PreviousPage $previousPage, IlotRepository $ilotRepository, User $user = null): Response
    {
        // ParamConverter => si $user = null, alors notre contrôleur est exécuté
        if (null === $user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        return $this->render('user/read.html.twig', [
            'user' => $user,
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/user/{id<\d+>}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(
        Request                     $request,
        EntityManagerInterface      $em,
        UserPasswordHasherInterface $userPasswordHasher,
        PreviousPage                $previousPage,
        IlotRepository              $ilotRepository,
        User                        $user = null): Response
    {
        if (null === $user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->add('user', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'prrofile-add btn-success'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($form->get('password')->getData())) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashedPassword);
            }

            $em->flush();

            $this->addFlash('success', $user->getUserIdentifier() . ' : votre profil utilisateur a bien été mis à jour.');

            return $this->redirectToRoute('user_read', ['id' => $user->getId()], 302);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }
}
