<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * 🔐 IMPORTANT :
     * L’authenticator ne s’exécute QUE pour le formulaire login
     */
    public function supports(Request $request): bool
    {
        return $request->isMethod('POST')
            && $request->request->get('type') === 'login';
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // 🔎 Vérification email
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneByEmail($email);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException(
                '⚠️ L’email renseigné n’existe pas.'
            );
        }

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        /** @var SessionInterface $session */
        $session = $request->getSession();
        $session->getFlashBag()->add(
            'info-alert',
            'Heureux de vous revoir 👋'
        );

        return new RedirectResponse(
            $this->urlGenerator->generate('app_home')
        );
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): Response {

        /** @var SessionInterface $session */
        $session = $request->getSession();

        $message = $exception instanceof CustomUserMessageAuthenticationException
            ? $exception->getMessage()
            : '⚠️ Mot de passe incorrect. Veuillez réessayer.';

        $session->getFlashBag()->add('info-alert', $message);

        return new RedirectResponse(
            $this->urlGenerator->generate('app_home')
        );
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_home');
    }
}
