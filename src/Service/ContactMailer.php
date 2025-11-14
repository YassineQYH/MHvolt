<?php

namespace App\Service;

use App\Classe\Mail;
use Twig\Environment;

class ContactMailer
{
    private Mail $mail;
    private Environment $twig;

    public function __construct(Mail $mail, Environment $twig)
    {
        $this->mail = $mail;
        $this->twig = $twig;
    }

    public function sendAdminMail(array $data): void
    {
        $content = $this->twig->render('emails/contact_admin.html.twig', [
            'name' => $data['name'],
            'company' => $data['company'],
            'tel' => $data['tel'],
            'email' => $data['email'],
            'message' => $data['message'],
        ]);

        $this->mail->send(
            'yassine.qyh@gmail.com', // admin
            'HichTrott',
            'Vous avez reçu une nouvelle demande de contact',
            $content
        );
    }

    public function sendUserMail(array $data): void
    {
        $content = $this->twig->render('emails/contact_user.html.twig', [
            'name' => $data['name'],
            'company' => $data['company'],
            'tel' => $data['tel'],
            'email' => $data['email'],
            'message' => $data['message'],
        ]);

        $this->mail->send(
            $data['email'], // utilisateur
            'HichTrott',
            'Confirmation de votre message à HichTrott',
            $content
        );
    }

    public function sendContactMails(array $data): void
    {
        $this->sendAdminMail($data);
        $this->sendUserMail($data);
    }
}
