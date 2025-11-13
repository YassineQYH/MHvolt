<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('info/cgv.html.twig');
    }

    #[Route('/politique-confidentialite', name: 'app_confidentialite')]
    public function confidentialite(): Response
    {
        return $this->render('info/politique_confidentialite.html.twig');
    }

    #[Route('/livraison-retours', name: 'app_livraison_retours')]
    public function livraisonRetours(): Response
    {
        return $this->render('info/livraison_retours.html.twig');
    }
}
