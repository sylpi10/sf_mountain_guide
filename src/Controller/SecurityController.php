<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private AuthenticationUtils $util;

    public function __construct(AuthenticationUtils $util)
    {
        $this->util = $util;
    }

    #[Route(path: '/login', name: 'login')]
    public function login(): Response
    {
        // déjà connecté : inutile de réafficher le formulaire
        if ($this->getUser()) {
            return $this->redirectToRoute($this->isGranted('ROLE_ADMIN') ? 'admin' : 'home');
        }

        return $this->render('global/login.html.twig', [
            'lastUserName' => $this->util->getLastUsername(),
            'error' => $this->util->getLastAuthenticationError(),
            'displayBtn' => true,
        ]);

        // $submittedToken = $request->request->get('token');
        // if ($this->isCsrfTokenValid('authenticate', $submittedToken)) {
        //     return $this->render('global/index.html.twig');
        // }
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout()
    {
    }
}
