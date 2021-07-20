<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\UserOwnedCard;
use App\Entity\UserWantedCard;
use App\Form\CardQuickSearchFormType;
use App\Repository\CardRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
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
        $user = $security->getUser();
        $user->addWantedCard($card);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_wanted_cards', ['user' => $user->getId()]);
    }

    public function deWantsCard(Card $card, Security $security)
    {
        $user = $security->getUser();
        $user->removeWantedCard($card);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_wanted_cards', ['user' => $user->getId()]);
    }

    public function ownsCard(Card $card, Security $security)
    {
        $user = $security->getUser();
        $user->addOwnedCard($card);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_owned_cards', ['user' => $user->getId()]);
    }

    public function deOwnsCard(Card $card, Security $security)
    {
        $user = $security->getUser();
        $user->removeOwnedCard($card);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_owned_cards', ['user' => $user->getId()]);
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

    public function saveWanted(UserWantedCard $wantedCard, Request $request, Security $security): Response
    {
        $content = json_decode($request->getContent());

        if (!empty($content->field) && !empty($content->fieldValue)) {
            switch ($content->field) {
                case 'number':
                    $wantedCard->setNumber($content->fieldValue);
                    break;

                case 'language':
                    $wantedCard->setLanguage($content->fieldValue);
                    break;
            }
        }

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')
            && $security->getUser() instanceof UserInterface
            && $security->getUser()->getUserWantedCards()->contains($wantedCard)
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wantedCard);
            $entityManager->flush();
        }

        return new Response();
    }

    public function saveOwned(UserOwnedCard $ownedCard, Request $request, Security $security): Response
    {
        $content = json_decode($request->getContent());

        if (!empty($content->field) && isset($content->fieldValue)) {
            switch ($content->field) {
                case 'number':
                    $ownedCard->setNumber($content->fieldValue);
                    break;

                case 'for_trade':
                    $ownedCard->setIsForTrade(($content->fieldValue === 1));
                    break;

                case 'language':
                    $ownedCard->setLanguage($content->fieldValue);
                    break;
            }
        }

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')
            && $security->getUser() instanceof UserInterface
            && $security->getUser()->getUserOwnedCards()->contains($ownedCard)
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ownedCard);
            $entityManager->flush();
        }

        return new Response();
    }
}
