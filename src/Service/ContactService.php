<?php

namespace App\Service;

use App\Service\ContactMailer;

class ContactService
{
    private ContactMailer $contactMailer;

    public function __construct(ContactMailer $contactMailer)
    {
        $this->contactMailer = $contactMailer;
    }

    /**
     * Traite le formulaire de contact : vérifie le honeypot et envoie les mails.
     * Retourne true si le mail a été envoyé, false si spam détecté.
     */
    public function handleContactForm(array $data): bool
    {
        // Vérification honeypot
        if (!empty($data['honeypot'] ?? null)) {
            return false; // Spam détecté
        }

        // Envoi des mails
        $this->contactMailer->sendContactMails($data);

        return true;
    }
}
