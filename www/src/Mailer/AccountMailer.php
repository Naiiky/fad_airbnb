<?php

namespace App\Mailer;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class AccountMailer
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $mailerFrom,
    ) {
    }

    public function sendWelcomeEmail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->mailerFrom, 'Havre'))
            ->to(new Address($user->getEmail(), trim($user->getFirstname().' '.$user->getLastname()) ?: $user->getEmail()))
            ->subject('Bienvenue sur Havre')
            ->htmlTemplate('emails/welcome.html.twig')
            ->context([
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }
}
