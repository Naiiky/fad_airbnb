<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationRequest
{
    #[Assert\NotBlank(message: 'Veuillez saisir votre prénom.')]
    #[Assert\Length(max: 255)]
    public string $firstname = '';

    #[Assert\NotBlank(message: 'Veuillez saisir votre nom.')]
    #[Assert\Length(max: 255)]
    public string $lastname = '';

    #[Assert\NotBlank(message: 'Veuillez saisir votre date de naissance.')]
    public ?\DateTimeImmutable $birthDate = null;

    #[Assert\NotBlank(message: 'Veuillez saisir votre adresse e-mail.')]
    #[Assert\Email(message: 'Veuillez saisir une adresse e-mail valide.')]
    #[Assert\Length(max: 150)]
    public string $email = '';

    #[Assert\NotBlank(message: 'Veuillez saisir un mot de passe.')]
    #[Assert\Length(min: 8, max: 4096, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.')]
    public string $plainPassword = '';

    #[Assert\IsTrue(message: 'Vous devez accepter les conditions générales.')]
    public bool $termsAccepted = false;

    #[Assert\IsTrue(message: 'Vous devez accepter la politique de confidentialité.')]
    public bool $privacyAccepted = false;
}
