<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class EmailService
{
    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendSignupEmail(string $recipientEmail)
    {
        $email = (new TemplatedEmail())
            ->from('your-email@example.com')
            ->to($recipientEmail)
            ->subject('Welcome to Your App')
            ->htmlTemplate('emails/signup.html.twig')
            ->context([]);

        $this->mailer->send($email);
    }
}