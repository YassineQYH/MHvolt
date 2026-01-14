<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestMailController extends AbstractController
{
    #[Route('/test-mail', name: 'test_mail')]
    public function index(): Response
    {
        $mail = new Mail();
        $success = $mail->send(
            'yassine.qyh@gmail.com', 
            'Yass',
            'Test Mailjet',
            '<p>Bonjour, ceci est un test depuis Symfony + Mailjet !</p>'
        );

        return new Response($success ? 'Mail envoyé ✅' : 'Erreur ❌');
    }
}
