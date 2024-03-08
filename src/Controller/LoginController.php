<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{



    // #[Route('/admin', name: 'admin_dashboard')]
    // public function admin(): Response
    // {
    //     return $this->render('admin/admin.html.twig', [
    //         'controller_name' => 'SecurityController',
    //     ]);
    // }


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
      // Check if the user is already authenticated
    if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
        // Redirect the user to the admin dashboard
        return $this->redirectToRoute('admin_dashboard');
    }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
    
        // Create the login form
        $form = $this->createForm(LoginFormType::class);
    
        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('exceptionn !!!!!');
    }



    #[Route('/admin-login', name: 'admin_login')]
    public function admin_login(): Response
    {
        return $this->render('admin/admin-login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

}
