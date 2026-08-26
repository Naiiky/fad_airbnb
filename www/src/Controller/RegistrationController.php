<?php

namespace App\Controller;

use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\User;
use App\Entity\UserStatus;
use App\Form\Model\RegistrationRequest;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AgeMajorityChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        AgeMajorityChecker $ageMajorityChecker,
    ): Response {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $registration = new RegistrationRequest();
        $form = $this->createForm(RegistrationFormType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ('' !== $registration->email && null !== $userRepository->findOneBy(['email' => $registration->email])) {
                $form->get('email')->addError(new FormError('Cette adresse e-mail est déjà utilisée.'));
            }

            if (null !== $registration->birthDate && !$ageMajorityChecker->isAdult($registration->birthDate)) {
                $form->get('birthDate')->addError(new FormError("Vous devez avoir 18 ans révolus pour vous inscrire."));
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $activeStatus = $entityManager->getRepository(UserStatus::class)->findOneBy(['label' => 'ACTIVE']);
            $pendingAgeStatus = $entityManager->getRepository(AgeVerificationStatus::class)->findOneBy(['label' => 'PENDING']);
            $france = $entityManager->getRepository(Country::class)->findOneBy(['label' => 'France']);

            if (!$activeStatus || !$pendingAgeStatus || !$france) {
                throw $this->createNotFoundException('Les référentiels nécessaires à l’inscription sont absents.');
            }

            $user = new User(
                mb_strtolower($registration->email),
                'temporary-password',
                $registration->firstname,
                $registration->lastname,
                $activeStatus,
                $pendingAgeStatus,
                $france,
            );
            $user
                ->setProfile($registration->firstname, $registration->lastname, birthDate: $registration->birthDate)
                ->setRoles(['ROLE_USER'])
                ->verifyEmail()
                ->acceptTerms(new \DateTimeImmutable())
                ->setPassword($passwordHasher->hashPassword($user, $registration->plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été créé. Vous pouvez vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ], new Response($form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }
}
