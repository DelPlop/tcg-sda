<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardQuickSearchFormType;
use App\Repository\CardRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

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

    public function quickSearchForm(Environment $twig): Response
    {
        $form = $this->createForm(CardQuickSearchFormType::class, [], [
            'action' => $this->generateUrl('cards_search'),
            'attr' => [
                'id' => 'cards_search',
                'class' => 'w3-show-inline-block'
            ]
        ]);

        return new Response($twig->render('card/quick-search-form.html.twig', [
            'form' => $form->createView()
        ]));
    }

    public function search(Request $request, CardRepository $cardRepository): Response
    {
        $form = $this->createForm(CardQuickSearchFormType::class, [], [
            'action' => $this->generateUrl('cards_search'),
            'attr' => [
                'id' => 'cards_search',
                'class' => 'w3-show-inline-block'
            ]
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $cards = $cardRepository->searchCards($data['search']);

            if (count($cards) == 1) {
                return $this->redirectToRoute('card_show', ['card' => $cards[0]->getId()]);
            } else {
                return $this->render('card/list.html.twig', [
                    'activePage' => 'search',
                    'cards' => $cards
                ]);
            }
        } else {
            return $this->redirectToRoute('index');
        }
    }

    public function advancedSearch(): Response
    {
        return $this->render('card/advancedSearch.html.twig', [
            'activePage' => 'search'
        ]);
    }
}
