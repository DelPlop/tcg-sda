<?php

namespace App\Controller;

use App\Entity\Card;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardController extends AbstractController
{
    public function show(Card $card): Response
    {
        return $this->render('card/show.html.twig', [
            'activePage' => 'editions',
            'card' => $card
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
