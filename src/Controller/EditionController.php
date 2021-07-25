<?php

namespace App\Controller;

use App\Entity\Edition;
use App\Repository\EditionRepository;
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

    public function cards(Edition $edition): Response
    {
        return $this->render('edition/cards.html.twig', [
            'activePage' => 'editions',
            'edition' => $edition,
            'cards' => $edition->getCards()
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
