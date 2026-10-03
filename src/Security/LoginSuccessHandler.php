<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Après connexion : retour à la page protégée demandée s'il y en a une,
 * sinon /admin pour un admin et l'accueil pour les autres.
 */
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    use TargetPathTrait;

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $token->getFirewallName())) {
            $this->removeTargetPath($request->getSession(), $token->getFirewallName());

            return new RedirectResponse($targetPath);
        }

        $route = in_array('ROLE_ADMIN', $token->getRoleNames(), true) ? 'admin' : 'home';

        return new RedirectResponse($this->urlGenerator->generate($route));
    }
}
