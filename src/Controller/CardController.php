<?php

namespace App\Controller;

use App\Entity\Card;
use App\Repository\CardRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class CardController extends AbstractController
{
    public function show(Card $card, CardRepository $cardRepository): Response
    {
        return $this->render('card/show.html.twig', [
            'activePage' => 'editions',
            'card' => $card,
            'first' => $cardRepository->findFirstCard($card),
            'prev' => $cardRepository->findPreviousCard($card) ?: $cardRepository->findFirstCard($card),
            'next' => $cardRepository->findNextCard($card) ?: $cardRepository->findLastCard($card),
            'last' => $cardRepository->findLastCard($card)
        ]);
    }

    public function wantsCard(Card $card, Security $security)
    {
        $security->getUser()->addWantedCard($card);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($security->getUser());
        $entityManager->flush();

        return $this->redirectToRoute('card_show', ['card' => $card->getId()]);
    }

    public function ownsCard(Card $card, Security $security)
    {
        $security->getUser()->addOwnedCard($card);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($security->getUser());
        $entityManager->flush();

        return $this->redirectToRoute('card_show', ['card' => $card->getId()]);
    }

    public function search(): Response
    {
        return new Response();
    }

    public function advancedSearch(): Response
    {
        return new Response();
    }
}
