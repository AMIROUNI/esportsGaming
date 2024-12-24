<?php

namespace App\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('_username', '');
        $password = $request->request->get('_password', '');
        $csrfToken = $request->request->get('_csrf_token');

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();

        if (in_array('ROLE_GAMER', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_produit_index'));
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
        }

        if (in_array('ROLE_COACH', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_coach_dashboard'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_homepage'));
    }

//    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
//    {
//        $data = [
//            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
//        ];
//
//        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
//    }

}
