<?php

namespace App\Service;

use App\Classe\Mail;
use Twig\Environment;

class ContactMailer
{
    public function __construct(
        private readonly Mail $mail,
        private readonly Environment $twig
    ) {}

    public function sendAdminMail(array $data): void
    {
        $content = $this->twig->render('emails/contact_admin.html.twig', [
            'name' => $data['name'] ?? '',
            'company' => $data['company'] ?? '',
            'tel' => $data['tel'] ?? '',
            'email' => $data['email'] ?? '',
            'message' => $data['message'] ?? '',
        ]);

        $adminEmail = $_ENV['ADMIN_EMAIL'] ?? 'admin@mhvolt.com';

        $this->mail->send(
            $adminEmail,
            'MHvolt Administration',
            'Nouvelle demande de contact reçue',
            $content
        );
    }

    public function sendUserMail(array $data): void
    {
        $content = $this->twig->render('emails/contact_user.html.twig', [
            'name' => $data['name'] ?? '',
            'company' => $data['company'] ?? '',
            'tel' => $data['tel'] ?? '',
            'email' => $data['email'] ?? '',
            'message' => $data['message'] ?? '',
        ]);

        $this->mail->send(
            $data['email'],
            $data['name'] ?? 'Client',
            'Confirmation de votre message à MHvolt',
            $content
        );
    }

    public function sendContactMails(array $data): void
    {
        $this->sendAdminMail($data);
        $this->sendUserMail($data);
    }
}
