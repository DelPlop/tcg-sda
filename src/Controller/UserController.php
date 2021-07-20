<?php

namespace App\Controller;

use App\Entity\ApplicationUser;
use App\Form\UserContactFormType;
use App\Form\UserQuickLoginFormType;
use App\Repository\UserOwnedCardRepository;
use App\Repository\UserWantedCardRepository;
use DelPlop\UserBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

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

    public function quickLoginForm(Environment $twig): Response
    {
        $form = $this->createForm(UserQuickLoginFormType::class, [], [
            'action' => $this->generateUrl('login'),
            'attr' => [
                'id' => 'login_form',
                'class' => 'w3-show-inline-block'
            ]
        ]);

        return new Response($twig->render('user/quick-login-form.html.twig', [
            'form' => $form->createView()
        ]));
    }

    public function contact(ApplicationUser $user, Environment $twig, Request $request, Security $security): Response
    {
        $form = $this->createForm(UserContactFormType::class, [], [
            'action' => $this->generateUrl('user_contact', ['user' => $user->getId()]),
            'attr' => [
                'id' => 'contact_form',
            ]
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $subject = "Quelqu'un vous contacte depuis Le TCG du Seigneur des Anneaux";
            $dest = $user->getLogin().' <'.$user->getEmail().'>';
            $from = $security->getUser()->getLogin().' <'.$security->getUser()->getEmail().'>';

            $content = "Bonjour ".$user->getLogin().",\n";
            $content .= $security->getUser()->getLogin()." vous a envoyé un message depuis <a href=\"https://www.tcg-seigneur-des-anneaux.fr\">Le TCG du Seigneur des Anneaux</a>.\n\n";
            $content .= "Voici son message :\n<pre>" . $this->cleanEntry($data['message']) . "</pre>\n\n";
            $content .= "Pour lui répondre, vous pouvez écrire à <a href=\"mailto:".$security->getUser()->getEmail()."\">".$security->getUser()->getEmail()."</a>.\n\n";
            $content .= "Bonne journée et à bientôt sur <a href=\"https://www.tcg-seigneur-des-anneaux.fr\">Le TCG du Seigneur des Anneaux</a> !";

            $headers = array(
                'From'         => $from,
                'Reply-To'     => $from,
                'Content-type' => 'text/html; charset=utf-8'
            );

            mail($dest, $subject, nl2br($content), $headers);

            return new Response($twig->render('user/contact.html.twig', [
                'sent' => true,
                'activePage' => 'users'
            ]));
        }

        return new Response($twig->render('user/contact.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'activePage' => 'users'
        ]));
    }

    public function exportOwnedCards(ApplicationUser $user)
    {
        $filename = 'cartes_'.$this->cleanEntry($user->getLogin(), true).'.csv';

        $head = [
            'Nom',
            'Code',
            'Nombre d\'exemplaire',
            'Version / langue',
            'A l\'échange'
        ];

        $cards = [];
        foreach ($user->getUserOwnedCards() as $card) {
            $cards[] = [
                $card->getCard()->getLocalName() ?: $card->getCard()->getOriginalName(),
                $card->getCard()->getCode(),
                $card->getNumber(),
                $card->getLanguage(),
                $card->getIsForTrade() ? 'oui' : 'non'
            ];
        }

        $this->exportFile($filename, $head, $cards);
    }

    public function exportWantedCards(ApplicationUser $user)
    {
        $filename = 'recherches_'.$this->cleanEntry($user->getLogin(), true).'.csv';

        $head = [
            'Nom',
            'Code',
            'Nombre d\'exemplaire',
            'Version / langue'
        ];

        $cards = [];
        foreach ($user->getUserWantedCards() as $card) {
            $cards[] = [
                $card->getCard()->getLocalName() ?: $card->getCard()->getOriginalName(),
                $card->getCard()->getCode(),
                $card->getNumber(),
                $card->getLanguage()
            ];
        }

        $this->exportFile($filename, $head, $cards);
    }

    protected function exportFile(string $filename, array $head, array $cards)
    {
        $filepath = './exports/'.$filename;
        $handle = fopen($filepath, 'w');

        fputcsv($handle, $head, ';');

        foreach ($cards as $card) {
            fputcsv($handle, $card, ';');
        }
        fclose($handle);

        header('Content-Description: File Transfer');
        header("Content-Type: application/csv") ;
        header("Content-Disposition: attachment; filename=".$filename);
        header("Pragma: no-cache");
        header("Expires: 0");

        readfile($filepath);
    }

    protected function cleanEntry(string $text = '', bool $replaceSpace = false): string
    {
        $text = strtolower(trim($text));
        $text = strip_tags($text);
        $text = htmlentities($text);

        if ($replaceSpace) {
            $text = str_replace(' ', '_', $text);
        }

        return $text;
    }
}
