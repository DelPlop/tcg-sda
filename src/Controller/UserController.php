<?php

namespace App\Controller;

use App\Entity\ApplicationUser;
use App\Repository\UserOwnedCardRepository;
use App\Repository\UserWantedCardRepository;
use DelPlop\UserBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function list(UserRepository $userRepository): Response
    {
        return $this->render('user/list.html.twig', [
            'activePage' => 'users',
            'users' => $userRepository->findBy(['anonymizedAt' => null])
        ]);
    }

    public function show(ApplicationUser $user): Response
    {
        return $this->render('user/show.html.twig', [
            'activePage' => 'lists',
            'user' => $user
        ]);
    }

    public function ownedCards(ApplicationUser $user, UserOwnedCardRepository $repository): Response
    {
        return $this->render('card/owned.html.twig', [
            'activePage' => 'lists',
            'user' => $user,
            'cards' => $repository->findCards($user)
        ]);
    }

    public function wantedCards(ApplicationUser $user, UserWantedCardRepository $repository): Response
    {
        return $this->render('card/wanted.html.twig', [
            'activePage' => 'lists',
            'user' => $user,
            'cards' => $repository->findCards($user)
        ]);
    }

    public function contact(ApplicationUser $user): Response
    {
        return new Response();
    }
}
