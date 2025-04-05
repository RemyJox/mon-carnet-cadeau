<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user/profil', name: 'app_user_')]
final class UserController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app__index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        
        if($user != '/{id}'){
            return $this->redirectToRoute('show', [], Response::HTTP_SEE_OTHER);
        }
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        User $user,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
       
        $passwordForm = $this->createForm(ChangePasswordFormType::class);
        $passwordForm->handleRequest($request);
    
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $user->setRoles(['ROLE_USER']);
            $this->addFlash('success', 'Informations utilisateur mises à jour avec succès');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
    
        
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $data = $passwordForm->getData();
            
            
            if (!$passwordHasher->isPasswordValid($user, $data['oldPassword'])) {
                $this->addFlash('error', 'Mot de passe actuel incorrect');
                return $this->redirectToRoute('edit', ['id' => $user->getId()]);
            }
            
            
            $newPassword = $passwordHasher->hashPassword($user, $data['newPassword']);
            $user->setPassword($newPassword);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès');
            return $this->redirectToRoute('edit', ['id' => $user->getId()]);
        }
        
    
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'passwordForm' => $passwordForm->createView(),
        ]);
    }
    

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
