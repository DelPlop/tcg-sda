<?php

namespace App\Controller;

use App\Entity\Card;
use App\Repository\CardRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardController extends AbstractController
{
    public function show(Card $card, CardRepository $cardRepository): Response
    {
        return $this->render('card/show.html.twig', [
            'activePage' => 'editions',
            'card' => $card,
            'first' => $cardRepository->findFirstCard($card),
            'prev' => $cardRepository->findPreviousCard($card),
            'next' => $cardRepository->findNextCard($card),
            'last' => $cardRepository->findLastCard($card)
        ]);
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
