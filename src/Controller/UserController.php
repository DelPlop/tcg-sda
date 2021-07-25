<?php

namespace App\Controller;

use App\Entity\ApplicationUser;
use App\Form\UserContactFormType;
use App\Form\UserEditFormType;
use App\Form\UserQuickLoginFormType;
use App\Repository\UserOwnedCardRepository;
use App\Repository\UserWantedCardRepository;
use DelPlop\UserBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
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
        $form = $this->createForm(UserEditFormType::class, $user, [
            'action' => $this->generateUrl('user_edit', ['user' => $user->getId()])
        ]);

        return $this->render('user/show.html.twig', [
            'activePage' => 'lists',
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    public function edit(ApplicationUser $user, Request $request, Security $security, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserEditFormType::class, $user, [
            'action' => $this->generateUrl('user_edit', ['user' => $user->getId()])
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($security->isGranted('IS_AUTHENTICATED_FULLY')
                && $security->getUser() instanceof UserInterface
                && $security->getUser() === $user
            ) {
                $user->setPassword(
                    $passwordEncoder->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('user_show', ['user' => $user->getId()]);
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
            'activePage' => 'users',
            'sent' => false
        ]));
    }

    public function exportOwnedCards(ApplicationUser $user): Response
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
        foreach ($repository->findCards($user) as $card) {
            $cards[] = [
                $card->getCard()->getLocalName() ?: $card->getCard()->getOriginalName(),
                $card->getCard()->getCode(),
                $card->getNumber(),
                $card->getLanguage(),
                $card->getIsForTrade() ? 'oui' : 'non'
            ];
        }

        return $this->exportFile($filename, $head, $cards);
    }

    public function exportWantedCards(ApplicationUser $user): Response
    {
        $filename = 'recherches_'.$this->cleanEntry($user->getLogin(), true).'.csv';

        $head = [
            'Nom',
            'Code',
            'Nombre d\'exemplaire',
            'Version / langue'
        ];

        $cards = [];
        foreach ($repository->findCards($user) as $card) {
            $cards[] = [
                $card->getCard()->getLocalName() ?: $card->getCard()->getOriginalName(),
                $card->getCard()->getCode(),
                $card->getNumber(),
                $card->getLanguage()
            ];
        }

        return $this->exportFile($filename, $head, $cards);
    }

    protected function exportFile(string $filename, array $head, array $cards): StreamedResponse
    {
        $response = new StreamedResponse();

        $response->setCallback(function () use ($cards, $head, $filename) {
            $handle = fopen('php://output', 'w+');

            fputcsv($handle, $head, ';');
            foreach ($cards as $card) {
                fputcsv($handle, $card, ';');
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $filename,
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
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
