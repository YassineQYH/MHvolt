<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\User;
use App\Entity\Accessory;
use App\Entity\Trottinette;
use App\Entity\Address;
use App\Form\ContactType;
use App\Form\RegisterType;
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
        AuthenticationUtils $authenticationUtils
    ): Response
    {
        $cart = $cart->getFull();

        // --- FORMULAIRE DE CONTACT ---
        $formcontact = $this->createForm(ContactType::class);
        $formcontact->handleRequest($request);

        if ($formcontact->isSubmitted() && $formcontact->isValid()) {
            $this->addFlash('notice', "Merci de m'avoir contacté. Je vous répondrai dans les meilleurs délais.");

            $data = $formcontact->getData();
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

        // --- LOGIN ---
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // --- INSCRIPTION ---
        $notification = null;
        $user = new User();

        // ⚡ Ajouter une adresse vide par défaut pour le formulaire
        /* $user->addAddress(new Address()); */

        $formregister = $this->createForm(RegisterType::class, $user, [
            'by_reference' => false
        ]);
        $formregister->handleRequest($request);

        if ($formregister->isSubmitted() && $formregister->isValid()) {
            $user = $formregister->getData();

            // Vérifier si l'email existe déjà
            $search_email = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($user->getEmail());

            if (!$search_email) {
                $password = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                // ⚡ Les adresses sont persistées automatiquement grâce à cascade persist
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte.";
            } else {
                $notification = "L'email que vous avez renseigné existe déjà.";
            }
        }

        // --- DONNÉES POUR LE CARROUSEL ---
        $headers = $this->entityManager->getRepository(Trottinette::class)
            ->findBy(['isHeader' => true]);

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

        return $this->render('home/index.html.twig', [
            'headers' => $headers,
            'trottinettes' => $trottinettes,
            'accessories' => $accessories,
            'cart' => $cart,
            'formcontact' => $formcontact->createView(),
            'formregister' => $formregister->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
            'notification' => $notification,
            'trottinettes_menu' => $trottinettesMenu,
        ]);
    }
}
