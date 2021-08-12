<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class StaticController extends AbstractController
{
    public function rules(): Response
    {
        return $this->render('static/rules.html.twig', [
            'activePage' => 'rules'
        ]);
    }

    public function qa(): Response
    {
        return $this->render('static/qa.html.twig', [
            'activePage' => 'qa'
        ]);
    }

    public function thanks(): Response
    {
        return $this->render('static/thanks.html.twig', [
            'activePage' => 'thanks'
        ]);
    }

    public function topRight(Security $security, bool $withSearch = true): Response
    {
        if ($security->isGranted('IS_AUTHENTICATED_REMEMBERED') && $security->getUser() instanceof UserInterface) {
            $security->getUser()->setUpdatedAt(new \DateTime('now'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($security->getUser());
            $entityManager->flush();
        }

        return $this->render('static/topRight.html.twig', [
            'withSearch' => $withSearch
        ]);
    }

    public function bottomRight(bool $withMargin = true, bool $display = true): Response
    {
        return $this->render('static/bottomRight.html.twig', [
            'withMargin' => $withMargin,
            'display' => $display
        ]);
    }
}
