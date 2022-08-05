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
    /**
     * @Route("/users/index", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, IlotRepository $ilotRepository, PreviousPage $previousPage): Response
    {
        $connectedUser = $this->getUser()->getUserIdentifier();
        $userExistant = $userRepository->findOneBy(['email' => $connectedUser]);

        if ($this->getUser()->getRoles() !== ['ROLE_ADMIN']) {
            return $this->redirectToRoute('user_read', ['id' => $userExistant->getId()], 301);
        }

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/user/{id<\d+>}", name="read", methods={"GET"})
     */
    public function read(
        UserRepository $userRepository,
        PreviousPage   $previousPage,
        IlotRepository $ilotRepository,
        User           $user = null): Response
    {
        // ParamConverter => si $user = null, alors notre contrôleur est exécuté
        if (null === $user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $connectedUser = $this->getUser()->getUserIdentifier();
        $userExistant = $userRepository->findOneBy(['email' => $connectedUser]);

        if ($this->getUser()->getUserIdentifier() !== $user->getUserIdentifier() && $this->getUser()->getRoles() !== ['ROLE_ADMIN']) {
            $this->addFlash('danger', 'Vous ne pouvez pas voir le profil d\'un autre utilisateur.');

            return $this->redirectToRoute('user_read', ['id' => $userExistant->getId()], 301);
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
        UserRepository              $userRepository,
        User                        $user = null): Response
    {
        if (null === $user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $connectedUser = $this->getUser()->getUserIdentifier();
        $userExistant = $userRepository->findOneBy(['email' => $connectedUser]);

        if ($this->getUser()->getUserIdentifier() !== $user->getUserIdentifier() && $this->getUser()->getRoles() !== ['ROLE_ADMIN']) {
            $this->addFlash('danger', 'Vous ne pouvez pas modifier le profil d\'un autre utilisateur.');

            return $this->redirectToRoute('user_read', ['id' => $userExistant->getId()], 301);
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

            if ($this->getUser()->getUserIdentifier() !== $user->getUserIdentifier()) {
                $this->addFlash('success', 'Le profil utilisateur de ' . $user->getUserIdentifier() . ' a bien été mis à jour.');
            } else {
                $this->addFlash('success', 'Votre profil utilisateur a bien été mis à jour.');
            }

            return $this->redirectToRoute('user_read', ['id' => $user->getId()], 302);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/users/add", name="add", methods={"GET", "POST"})
     */
    public function add(
        Request                     $request,
        EntityManagerInterface      $em,
        UserPasswordHasherInterface $userPasswordHasher,
        PreviousPage                $previousPage,
        IlotRepository              $ilotRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->add('user', SubmitType::class, [
            'label' => 'Ajouter',
            'attr' => [
                'class' => 'user-add btn-primary'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur ' . $user->getUserIdentifier() . ' a bien été ajouté.');

            return $this->redirectToRoute('user_read', ['id' => $user->getId()], 302);
        }

        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
            'path' => $previousPage->pagePrecedente($ilotRepository)
        ]);
    }

    /**
     * @Route("/user/{id<\d+>}/delete", name="delete", methods={"GET"})
     */
    public function delete(EntityManagerInterface $em, User $user = null): Response
    {
        if (null === $user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        if ($this->getUser()->getUserIdentifier() !== $user->getUserIdentifier()) {
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur ' . $user->getUserIdentifier() . ' a bien été supprimé.');
        } else {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer votre profil utilisateur.');
        }

        return $this->redirectToRoute('user_index');
    }
}
