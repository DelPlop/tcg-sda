<?php

namespace App\Controller;

use App\Entity\Edition;
use App\Repository\CardRepository;
use App\Repository\EditionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditionController extends AbstractController
{
    public function editions(EditionRepository $editionRepository, bool $onlyVisible = true): Response
    {
        return $this->render('edition/left.html.twig', [
            'editions' => $editionRepository->findBy(['isDisplayable' => true], ['editionNumber' => 'asc'])
        ]);
    }

    public function cards(Edition $edition, Request $request, CardRepository $cardRepository): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $cardRepository->getPaginator($edition, $offset);

        return $this->render('edition/cards.html.twig', [
            'activePage' => 'editions',
            'edition' => $edition,
            'cards' => $paginator,
            'previous' => $offset - CardRepository::ITEM_PER_PAGE,
            'next' => min(count($paginator), $offset + CardRepository::ITEM_PER_PAGE)
        ]);
    }

    public function list(EditionRepository $editionRepository): Response
    {
        return $this->render('edition/list.html.twig', [
            'activePage' => 'editions',
            'editions' => $editionRepository->findBy(['isDisplayable' => true], ['editionNumber' => 'asc'])
        ]);
    }
}
