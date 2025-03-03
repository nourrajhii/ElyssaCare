<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailNotification(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('noreply@tonsite.com')
            ->to($to)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }
}
