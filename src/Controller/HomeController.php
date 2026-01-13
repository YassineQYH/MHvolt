<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\Accessory;
use App\Form\ContactType;
use App\Form\RegisterType;
use App\Entity\Trottinette;
use App\Service\PromotionService;
use App\Repository\PromotionRepository;
use App\Service\PromotionFinderService;
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
        Cart $cart,
        AuthenticationUtils $authenticationUtils,
        PromotionFinderService $promoFinder,
        PromotionService $promotionService,
    ): Response
    {
        $cart = $cart->getFull();

        // --- FORMULAIRE DE CONTACT ---
        $formcontact = $this->createForm(ContactType::class);
        $formcontact->handleRequest($request);

        if ($formcontact->isSubmitted() && $formcontact->isValid()) {

            // 🕵️‍♂️ Honeypot anti-bot
            $honeypot = $formcontact->get('honeypot')->getData();
            if (!empty($honeypot)) {
                $this->addFlash('error', "Spam détecté, message non envoyé.");
                return $this->redirectToRoute('app_home');
            }

            // 📬 Message flash utilisateur
            $this->addFlash('info-alert', "Merci de m'avoir contacté. Je vous répondrai dans les meilleurs délais.");

            $data = $formcontact->getData();
            $mail = new Mail();

            // --- Mail à l'admin ---
            $adminContent = $this->renderView('emails/contact_admin.html.twig', [
                'name' => $data['name'],
                'company' => $data['company'],
                'tel' => $data['tel'],
                'email' => $data['email'],
                'message' => $data['message'],
            ]);

            $mail->send(
                'yassine.qyh@gmail.com', // admin
                'HichTrott',
                'Vous avez reçu une nouvelle demande de contact',
                $adminContent
            );

            // --- Mail de confirmation à l'utilisateur ---
            $userContent = $this->renderView('emails/contact_user.html.twig', [
                'name' => $data['name'],
                'company' => $data['company'],
                'tel' => $data['tel'],
                'email' => $data['email'],
                'message' => $data['message'],
            ]);

            $mail->send(
                $data['email'], // utilisateur
                'HichTrott',
                'Confirmation de votre message à HichTrott',
                $userContent
            );

            // 🔄 Redirection OBLIGATOIRE pour afficher le message flash
            return $this->redirectToRoute('app_home');
        }

        if ($formcontact->isSubmitted() && !$formcontact->isValid()) {
            $this->addFlash(
                'error',
                "Une erreur est survenue. Veuillez vérifier les informations du formulaire de contact."
            );
        }

        // --- LOGIN ---
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // --- INSCRIPTION ---
        $notification = null;
        $user = new User();

        $formregister = $this->createForm(RegisterType::class, $user, [
            'by_reference' => false
        ]);
        $formregister->handleRequest($request);

        if ($formregister->isSubmitted() && $formregister->isValid()) {
            $user = $formregister->getData();

            $search_email = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($user->getEmail());

            if (!$search_email) {
                $password = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                // 🔹 AJOUT DU ROLE PAR DÉFAUT
                $user->setRoles(['ROLE_USER']);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // ✅ FLASH SUCCÈS
                $this->addFlash(
                    'info-alert',
                    "Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte."
                );

            } else {
                // ❌ FLASH ERREUR
                $this->addFlash(
                    'info-alert',
                    "L'email que vous avez renseigné existe déjà."
                );
            }

            // 🔄 IMPORTANT : redirection pour afficher le flash
            return $this->redirectToRoute('app_home');
        }


        // --- DONNÉES POUR LE CARROUSEL ---
        /* $headers = $this->entityManager->getRepository(Trottinette::class)
            ->findBy(['isHeader' => true]); */

        $video_header = $this->entityManager->getRepository(\App\Entity\HomeVideo::class)
                     ->findBy(['isActive' => true], ['position' => 'ASC']);


        // --- MENU PRINCIPAL : TROTTINETTES ---
        $trottinettesMenu = $this->entityManager->getRepository(Trottinette::class)->findAll();
        $uniqueTrottinettesMenu = [];
        foreach ($trottinettesMenu as $t) {
            $uniqueTrottinettesMenu[$t->getId()] = $t;
        }
        $trottinettesMenu = array_values($uniqueTrottinettesMenu);

        // --- SLIDERS BEST ---
        $trottinettes = $this->entityManager->getRepository(Trottinette::class)
            ->findBy(['isBest' => 1]);
        $accessories = $this->entityManager->getRepository(Accessory::class)
            ->findBy(['isBest' => 1]);

        // Trouver la promo à afficher sur la home (auto ou non)
        $homepagePromo = $promoFinder->findHomepagePromo();

        return $this->render('home/index.html.twig', [
            /* 'headers' => $headers, */
            'video_header' => $video_header,
            'trottinettes' => $trottinettes,
            'accessories' => $accessories,
            'cart' => $cart,
            'formcontact' => $formcontact->createView(),
            'formregister' => $formregister->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
            'notification' => $notification,
            'trottinettes_menu' => $trottinettesMenu,
            'homepagePromo' => $homepagePromo,
            'promoService' => $promotionService,
        ]);
    }
}
