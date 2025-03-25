<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        $email = (new Email())
            ->from("aura451555@gmail.com")
            ->to($to)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }

    public function sendEmailWithAttachment(string $to, string $subject, string $body, string $attachmentPath): void
    {
        $email = (new Email())
            ->from("aura451555@gmail.com")
            ->to($to)
            ->subject($subject)
            ->text($body)
            ->attachFromPath($attachmentPath);
    
        $this->mailer->send($email);
    }

    public function sendHtmlEmailWithAttachment(string $to, string $subject, string $htmlBody, string $attachmentPath): void {
        $email = (new Email())
            ->from("aura451555@gmail.com")
            ->to($to)
            ->subject($subject)
            ->html($htmlBody)
            ->attachFromPath($attachmentPath);
    
        $this->mailer->send($email);
    }
}