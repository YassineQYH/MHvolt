<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\User;
use App\Entity\Accessory;
use App\Form\ContactType;
use App\Form\RegisterType;
use App\Entity\Trottinette;
use App\Service\PromotionService;
use App\Entity\UserRegistrationToken;
use App\Service\PromotionFinderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        Cart $cart,
        AuthenticationUtils $authenticationUtils,
        PromotionFinderService $promoFinder,
        PromotionService $promotionService,
    ): Response
    {
        $cartFull = $cart->getFull();

        /* =====================================================
         * 📩 FORMULAIRE DE CONTACT
         * ===================================================== */
        $formcontact = $this->createForm(ContactType::class);
        $formcontact->handleRequest($request);

        if ($formcontact->isSubmitted() && $formcontact->isValid()) {
            if (!empty($formcontact->get('honeypot')->getData())) {
                $this->addFlash('error', "Spam détecté, message non envoyé.");
                return $this->redirectToRoute('app_home');
            }

            $this->addFlash(
                'info-alert',
                "Merci de m'avoir contacté. Je vous répondrai dans les meilleurs délais."
            );

            $data = $formcontact->getData();
            $mail = new Mail();

            $mail->send(
                'yassine.qyh@gmail.com',
                'HichTrott',
                'Vous avez reçu une nouvelle demande de contact',
                $this->renderView('emails/contact_admin.html.twig', $data)
            );

            $mail->send(
                $data['email'],
                'HichTrott',
                'Confirmation de votre message à HichTrott',
                $this->renderView('emails/contact_user.html.twig', $data)
            );

            return $this->redirectToRoute('app_home');
        }

        /* =====================================================
         * 🔐 LOGIN & INSCRIPTION
         * ===================================================== */
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = new User();
        $formregister = $this->createForm(RegisterType::class, $user);
        $formregister->handleRequest($request);

        if ($formregister->isSubmitted() && $formregister->isValid()) {
            $existingUser = $this->entityManager
                ->getRepository(User::class)
                ->findOneByEmail($user->getEmail());

            if ($existingUser) {
                $this->addFlash('info-alert', "L'email que vous avez renseigné existe déjà.");
                return $this->redirectToRoute('app_home');
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $token = bin2hex(random_bytes(32));

            $verificationUrl = $this->generateUrl(
                'verify_account',
                ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $registration = new UserRegistrationToken();
            $registration->setEmail($user->getEmail());
            $registration->setPasswordHash($hashedPassword);
            $registration->setFirstName($user->getFirstName());
            $registration->setLastName($user->getLastName());
            $registration->setTel($user->getTel());
            $registration->setToken($token);
            $registration->setExpiresAt(new \DateTimeImmutable('+24 hours'));

            $this->entityManager->persist($registration);
            $this->entityManager->flush();

            $mail = new Mail();
            $mail->send(
                $user->getEmail(),
                $user->getFirstName(),
                "Confirmation de votre inscription",
                $this->renderView('emails/confirm_registration.html.twig', [
                    'firstName' => $user->getFirstName(),
                    'verificationUrl' => $verificationUrl
                ])
            );

            $this->addFlash('info-alert', "Un email de confirmation vient de vous être envoyé. Veuillez valider votre compte.");
            return $this->redirectToRoute('app_home');
        }

        /* =====================================================
         * 🏠 DONNÉES PAGE D’ACCUEIL (CATALOGUE MIXTE)
         * ===================================================== */
        $video_header = $this->entityManager
            ->getRepository(\App\Entity\HomeVideo::class)
            ->findBy(['isActive' => true], ['position' => 'ASC']);

        $trottinettesMenu = $this->entityManager
            ->getRepository(Trottinette::class)
            ->findAll();

        // ⚡ Nouveautés (limités pour l'accueil, ex: 4 par catégorie)
        $latestTrottinettes = $this->entityManager
            ->getRepository(Trottinette::class)
            ->findBy([], ['id' => 'DESC'], 4);

        $latestAccessories = $this->entityManager
            ->getRepository(Accessory::class)
            ->findBy([], ['id' => 'DESC'], 4);

        $homepagePromo = $promoFinder->findHomepagePromo();

        return $this->render('home/index.html.twig', [
            'video_header' => $video_header,
            'latestTrottinettes' => $latestTrottinettes,
            'latestAccessories' => $latestAccessories,
            'cart' => $cartFull,
            'formcontact' => $formcontact->createView(),
            'formregister' => $formregister->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
            'homepagePromo' => $homepagePromo,
            'promoService' => $promotionService,
            'trottinettes_menu' => $trottinettesMenu,
        ]);
    }
}
