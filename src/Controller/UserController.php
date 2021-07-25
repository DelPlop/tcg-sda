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
use Symfony\Contracts\Translation\TranslatorInterface;
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

    public function contact(ApplicationUser $user, Environment $twig, Request $request, Security $security, TranslatorInterface $translator): Response
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

            $subject = $translator->trans('contact.object', [], 'cards') . ' ' . $translator->trans('general.site_name', [], 'cards');
            $dest = $user->getLogin().' <'.$user->getEmail().'>';
            $from = $security->getUser()->getLogin().' <'.$security->getUser()->getEmail().'>';

            $content = $translator->trans('general.hello_user', ['%username%' => $user->getLogin()], 'cards') . "\n";
            $content .= $translator->trans('contact.user_sent_you_from', ['%username%' => $security->getUser()->getLogin()], 'cards');
            $content .= " <a href=\"https://www.tcg-seigneur-des-anneaux.fr\">" . $translator->trans('general.site_name', [], 'cards') . "</a>.\n\n";
            $content .= $translator->trans('contact.the_message', [], 'cards');
            $content .= "\n<pre>" . $this->cleanEntry($data['message']) . "</pre>\n\n";
            $content .= $translator->trans('contact.write_to', [], 'cards');
            $content .= " <a href=\"mailto:".$security->getUser()->getEmail()."\">".$security->getUser()->getEmail()."</a>.\n\n";
            $content .= $translator->trans('contact.signature', [], 'cards');
            $content .= " <a href=\"https://www.tcg-seigneur-des-anneaux.fr\">" . $translator->trans('general.site_name', [], 'cards') . "</a>";
            dd($content);

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

    public function exportOwnedCards(ApplicationUser $user, UserOwnedCardRepository $repository, TranslatorInterface $translator): Response
    {
        $filename = 'cartes_'.$this->cleanEntry($user->getLogin(), true).'.csv';

        $head = [
            $translator->trans('card.name', [], 'cards'),
            $translator->trans('card.code', [], 'cards'),
            $translator->trans('fellowship.number', [], 'cards'),
            $translator->trans('fellowship.version', [], 'cards'),
            $translator->trans('fellowship.for_trade', [], 'cards')
        ];

        $cards = [];
        foreach ($repository->findCards($user) as $card) {
            $cards[] = [
                $card->getCard()->getLocalName() ?: $card->getCard()->getOriginalName(),
                $card->getCard()->getCode(),
                $card->getNumber(),
                $card->getLanguage(),
                $card->getIsForTrade() ? $translator->trans('card.options.yes', [], 'cards') : $translator->trans('card.options.no', [], 'cards')
            ];
        }

        return $this->exportFile($filename, $head, $cards);
    }

    public function exportWantedCards(ApplicationUser $user, UserWantedCardRepository $repository, TranslatorInterface $translator): Response
    {
        $filename = 'recherches_'.$this->cleanEntry($user->getLogin(), true).'.csv';

        $head = [
            $translator->trans('card.name', [], 'cards'),
            $translator->trans('card.code', [], 'cards'),
            $translator->trans('fellowship.number', [], 'cards'),
            $translator->trans('fellowship.version', [], 'cards')
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
