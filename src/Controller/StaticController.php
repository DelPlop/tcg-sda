<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    public function topRight(bool $withSearch = true): Response
    {
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
