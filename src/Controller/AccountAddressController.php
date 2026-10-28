<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/compte/adresses', name: 'account_address')]
    public function index(Cart $cart): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        return $this->render('account/address.html.twig', [
            'cart' => $cart->getFull(),
            'addresses' => $user->getAddresses()
        ]);
    }

    #[Route('/compte/ajouter-une-adresse', name: 'account_address_add')]
    public function add(Cart $cart, Request $request): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user);
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            // Si le panier contient des articles, on renvoie directement au tunnel de commande
            if (count($cart->getFull()) > 0) {
                return $this->redirectToRoute('cart');
            }

            $this->addFlash('success', 'Votre adresse a bien été enregistrée.');
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/compte/modifier-une-adresse/{id}', name: 'account_address_edit')]
    public function edit(Request $request, int $id, Cart $cart): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        $address = $this->entityManager->getRepository(Address::class)->find($id);
        if (!$address || $address->getUser() !== $user) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre adresse a bien été modifiée.');
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/compte/supprimer-une-adresse/{id}', name: 'account_address_delete')]
    public function delete(int $id): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        $address = $this->entityManager->getRepository(Address::class)->find($id);
        if ($address && $address->getUser() === $user) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
            $this->addFlash('info-alert', 'Adresse supprimée.');
        }

        return $this->redirectToRoute('account_address');
    }
}
