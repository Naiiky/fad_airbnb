<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account_show', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render('account/show.html.twig', [
            'user' => $this->getProfileUser(),
        ]);
    }

    #[Route('/account/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getProfileUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->get('languages')->setData($this->getSelectedLanguages($user));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedLanguages = $form->get('languages')->getData();
            if ($selectedLanguages instanceof Collection) {
                $selectedLanguages = $selectedLanguages->toArray();
            }

            try {
                $this->syncLanguages($user, \is_array($selectedLanguages) ? $selectedLanguages : []);
                $entityManager->flush();
                $user->setAvatarFile(null);
            } catch (\RuntimeException $exception) {
                $user->setAvatarFile(null);
                $form->get('avatarFile')->addError(new FormError($exception->getMessage()));

                return $this->render('account/edit.html.twig', [
                    'profileForm' => $form,
                    'user' => $user,
                ], new Response(Response::HTTP_UNPROCESSABLE_ENTITY));
            }

            $this->addFlash('success', 'Votre profil a été mis à jour.');

            return $this->redirectToRoute('app_account_show');
        }

        if ($form->isSubmitted()) {
            $user->setAvatarFile(null);
        }

        return $this->render('account/edit.html.twig', [
            'profileForm' => $form,
            'user' => $user,
        ], new Response($form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }

    private function getProfileUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        return $user;
    }

    /** @return list<Language> */
    private function getSelectedLanguages(User $user): array
    {
        return $user->getUserLanguages()
            ->map(static fn ($userLanguage) => $userLanguage->getLanguage())
            ->toArray();
    }

    /** @param list<Language> $selectedLanguages */
    private function syncLanguages(User $user, array $selectedLanguages): void
    {
        foreach ($user->getUserLanguages()->toArray() as $userLanguage) {
            if (!\in_array($userLanguage->getLanguage(), $selectedLanguages, true)) {
                $user->removeLanguage($userLanguage->getLanguage());
            }
        }

        foreach ($selectedLanguages as $language) {
            $user->addLanguage($language);
        }
    }
}
