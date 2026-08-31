<?php

namespace App\Controller;

use App\Entity\FavoriteProperty;
use App\Entity\Property;
use App\Entity\User;
use App\Repository\FavoritePropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/favorites', name: 'app_favorite_')]
#[IsGranted('ROLE_USER')]
class FavoriteController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(FavoritePropertyRepository $favoriteRepository): Response
    {
        return $this->render('favorite/index.html.twig', [
            'favorites' => $favoriteRepository->findForUser($this->getFavoriteUser()),
        ]);
    }

    #[Route('/properties/{id}', name: 'add', methods: ['POST'])]
    public function add(
        Request $request,
        Property $property,
        FavoritePropertyRepository $favoriteRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $this->validateCsrfToken($request, 'favorite_property_'.$property->getId());

        if ('PUBLISHED' !== $property->getStatus()->getLabel() || null !== $property->getDeletedAt()) {
            throw $this->createNotFoundException('Logement introuvable.');
        }

        $user = $this->getFavoriteUser();
        if ($property->getUser()->getId() === $user->getId()) {
            $this->addFlash('notice', 'Vous ne pouvez pas ajouter votre propre logement en favori.');

            return $this->redirectToRoute('app_property_show', ['id' => $property->getId()]);
        }

        if (!$favoriteRepository->findOneForUserAndProperty($user, $property) instanceof FavoriteProperty) {
            $entityManager->persist(new FavoriteProperty($user, $property));
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le logement a ete ajoute a vos favoris.');

        return $this->redirectToRoute('app_property_show', ['id' => $property->getId()]);
    }

    #[Route('/properties/{id}/remove', name: 'remove', methods: ['POST'])]
    public function remove(
        Request $request,
        Property $property,
        FavoritePropertyRepository $favoriteRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $this->validateCsrfToken($request, 'favorite_property_'.$property->getId());

        $favorite = $favoriteRepository->findOneForUserAndProperty($this->getFavoriteUser(), $property);
        if ($favorite instanceof FavoriteProperty) {
            $entityManager->remove($favorite);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le logement a ete retire de vos favoris.');

        return $this->redirectToRoute('app_favorite_index');
    }

    private function getFavoriteUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        return $user;
    }

    private function validateCsrfToken(Request $request, string $tokenId): void
    {
        if (!$this->isCsrfTokenValid($tokenId, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
    }
}
