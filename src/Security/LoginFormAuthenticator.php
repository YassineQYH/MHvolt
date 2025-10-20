<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Routing\Attribute\Route;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    

    private $entityManager;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        /**
         * @var SessionInterface
         */
        $session = $request->getSession();
        $session->getFlashBag()->add('success', 'Heureux de vous revoir');

        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        /**
         * @var SessionInterface
         */
        $session = $request->getSession();
        $session->getFlashBag()->add('error', strtr($exception->getMessageKey(), $exception->getMessageData()));

        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }
    
    protected function getLoginUrl(Request $request): string
    {
        if(isset($_POST["type"]) && $_POST["type"] =="login")
        //var_dump($_POST);
        //dd("if register");
        return $this->urlGenerator->generate($request->get('_route'), $request->get('_route_params')); // LOGIN : OK
        else
        //var_dump($_POST);
        //dd("if register");
        return new RedirectResponse('targetPath'); // REGISTER : OK 
    }

}
