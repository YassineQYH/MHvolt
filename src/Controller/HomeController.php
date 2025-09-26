<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Trottinette;
use App\Entity\Accessory;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $encoder,
        AuthenticationUtils $authenticationUtils
    ): Response
    {
        // --- FORMULAIRE DE CONTACT ---
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', "Merci de m'avoir contacté. Je vous répondrai dans les meilleurs délais.");

            $data = $form->getData();
            $content = "Bonjour </br>
                        Vous avez reçu un message depuis Pergolazur. </br>
                        De l'utilisateur : <strong>".$data['name']."</strong></br>
                        De la société : <strong>".$data['company']."</strong></br>
                        N° de tel : <strong>".$data['tel']."</strong></br>
                        Adresse email : <strong style='color:black;'>".$data['email']."</strong> </br>
                        Message : ".$data['message']."</br></br>";

            $mail = new Mail();
            $mail->send(
                'yassine.qyh@gmail.com',
                'Pergolazur',
                'Vous avez reçu une nouvelle demande de contact',
                $content
            );
        }

        // --- DONNÉES POUR LE CARROUSEL (ancien Header) ---
        $headers = $this->entityManager->getRepository(Trottinette::class)->findBy([
            'isHeader' => true
        ]);

        // --- MENU PRINCIPAL : TROTTINETTES ---
        $trottinettesMenu = $this->entityManager->getRepository(Trottinette::class)->findAll();

        // ⚡ éliminer les doublons par ID (sécurité)
        $uniqueTrottinettesMenu = [];
        foreach ($trottinettesMenu as $trottinette) {
            $uniqueTrottinettesMenu[$trottinette->getId()] = $trottinette;
        }
        $trottinettesMenu = array_values($uniqueTrottinettesMenu);

        // --- SLIDERS BEST ---
        $trottinettes = $this->entityManager->getRepository(Trottinette::class)->findBy(['isBest' => 1]);
        $accessories = $this->entityManager->getRepository(Accessory::class)->findBy(['isBest' => 1]);

        return $this->render('home/index.html.twig', [
            'headers' => $headers,                 // ⚡ trottinettes pour carrousel
            'trottinettes' => $trottinettes,       // slider "best" trottinettes
            'accessories' => $accessories,         // slider "best" accessoires
            'form' => $form->createView(),
            'trottinettes_menu' => $trottinettesMenu // menu principal sans doublons
        ]);
    }
}
