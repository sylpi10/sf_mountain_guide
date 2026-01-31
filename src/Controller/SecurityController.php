<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function __construct(
        AuthenticationUtils $util
    ) {
        $this->util = $util;
    }

    /**
     * @Route("/login", name="login")
     * @return Response
     */
    public function login(): Response
    {

        return $this->render('global/login.html.twig', [
            "lastUserName" => $this->util->getLastUsername(),
            "error" => $this->util->getLastAuthenticationError(),
            "displayBtn" => true
        ]);

        // $submittedToken = $request->request->get('token');
        // if ($this->isCsrfTokenValid('authenticate', $submittedToken)) {
        //     return $this->render('global/index.html.twig');
        // }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}
