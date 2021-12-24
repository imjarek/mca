<?php

namespace App\Shared\Infrastructure\Service;

use App\Domain\User\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerificationService implements IEmailVerificationService
{
    public function __construct(VerifyEmailHelperInterface $verificator, MailerInterface $mailer)
    {
        $this->verificator = $verificator;
        $this->mailer = $mailer;
    }

    public function sendEmailVerificationEmail(User $user): void
    {
        $signatureComponents = $this->verificator->generateSignature(
            'verify_email',
            $user->uuid(),
            $user->email()
        );

        $email = new TemplatedEmail();
        $email->from('send@example.com');
        $email->to($user->email());
        $email->htmlTemplate('email_confirmation.html.twig');
        $email->context([
            'user_name' => $user->getFirstName(),
            'link' => $signatureComponents->getSignedUrl()
        ]);

        $this->mailer->send($email);
    }
}