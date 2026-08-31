<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\User;
use App\Entity\UserStatus;
use App\Repository\PropertyRepository;
use App\Repository\PropertyStatusRepository;
use App\Repository\UserRepository;
use App\Repository\UserStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'app_admin_')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'dashboard', methods: ['GET'])]
    public function dashboard(UserRepository $userRepository, PropertyRepository $propertyRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'users' => $userRepository->findForAdmin(null, 1, 5),
            'properties' => $propertyRepository->findForAdmin(),
        ]);
    }

    #[Route('/users', name: 'users', methods: ['GET'])]
    public function users(Request $request, UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'pagination' => $userRepository->findForAdmin(
                $request->query->getString('q') ?: null,
                $request->query->getInt('page', 1),
            ),
            'query' => $request->query->getString('q'),
        ]);
    }

    #[Route('/users/{id}/suspend', name: 'user_suspend', methods: ['POST'])]
    public function suspendUser(
        Request $request,
        User $user,
        UserStatusRepository $statusRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $this->validateCsrfToken($request, 'suspend_user_'.$user->getId());

        if (\in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $this->addFlash('notice', 'Un administrateur ne peut pas etre suspendu depuis cette action.');

            return $this->redirectToRoute('app_admin_users');
        }

        $user->setStatus($this->getUserStatus($statusRepository, 'SUSPENDED'));
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur suspendu.');

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/properties', name: 'properties', methods: ['GET'])]
    public function properties(Request $request, PropertyRepository $propertyRepository): Response
    {
        $status = $request->query->getString('status');

        return $this->render('admin/properties.html.twig', [
            'properties' => $propertyRepository->findForAdmin($status ?: null),
            'current_status' => $status,
        ]);
    }

    #[Route('/properties/{id}/hide', name: 'property_hide', methods: ['POST'])]
    public function hideProperty(
        Request $request,
        Property $property,
        PropertyStatusRepository $statusRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $this->validateCsrfToken($request, 'admin_hide_property_'.$property->getId());

        $status = $statusRepository->findOneBy(['label' => 'HIDDEN']);
        if (null === $status) {
            throw new \RuntimeException('Le statut de logement "HIDDEN" est introuvable.');
        }

        $property->setStatus($status);
        $entityManager->flush();

        $this->addFlash('success', 'Logement masque.');

        return $this->redirectToRoute('app_admin_properties');
    }

    private function validateCsrfToken(Request $request, string $tokenId): void
    {
        if (!$this->isCsrfTokenValid($tokenId, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
    }

    private function getUserStatus(UserStatusRepository $statusRepository, string $label): UserStatus
    {
        $status = $statusRepository->findOneBy(['label' => $label]);
        if (!$status instanceof UserStatus) {
            throw new \RuntimeException(sprintf('Le statut utilisateur "%s" est introuvable.', $label));
        }

        return $status;
    }
}
