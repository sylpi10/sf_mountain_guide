<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    private AuthenticationUtils $util;

    public function __construct(AuthenticationUtils $util)
    {
        $this->util = $util;
    }

    /**
     * @return Response
     */
    #[Route(path: '/login', name: 'login')]
    public function login(): Response
    {
        return $this->render("global/login.html.twig", [
            "lastUserName" => $this->util->getLastUsername(),
            "error" => $this->util->getLastAuthenticationError(),
            "displayBtn" => true,
        ]);

        // $submittedToken = $request->request->get('token');
        // if ($this->isCsrfTokenValid('authenticate', $submittedToken)) {
        //     return $this->render('global/index.html.twig');
        // }
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout() {}
}
