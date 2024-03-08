<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\RegistrationType;
use App\Form\UserEditDetailsType;
use App\Form\UserPasswordType;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController extends AbstractController
{ 

    #[Route('/edit-admin/{id}', name: 'admin-details')]
    public function editAdmin(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        // Fetch the user from the database using the provided ID
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
    
        // Check if user exist
        if (!$user instanceof UserInterface) {
            throw $this->createNotFoundException('User not found');
        }
    
        $form = $this->createForm(UserProfileType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the user profile
            $entityManager->flush();
    
            // (reload)
            // return $this->redirectToRoute('edit-details', ['id' => $id]);
        }
    
        return $this->render('admin/edit-admin.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/updateUserIsGold/{id}', methods: ['POST'])]
    public function updateUserIsGold(int $id, UserRepository $userRepository, Request $request): Response
    {
        $user = $userRepository->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
        $isGold = $request->request->get('is_gold');
    
        // Update the user's is_gold property
        $user->setIsGold($isGold);
    
        // Save the changes to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
    
        // Return a response indicating the update was successful
        return new Response('User is_gold updated successfully.');
    }
    









    /**
     * @Route("/set-gold-member/{id}", name="set_gold_member")
     */
    public function setGoldMember(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setIsGold(true);
        $entityManager->flush();

        return $this->redirectToRoute('admin_dashboard'); // Replace 'homepage' with the route name of your desired redirect route
    }


    #[Route('/SeeUser/{id}', methods: ['GET'])]
    public function getUserById(int $id, UserRepository $userRepository, Environment $twig): Response
    {
        $user = $userRepository->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
        // Retrieve all necessary details of the user
        $userId = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $name = $user->getName();
        $is_gold = $user->isIsGold();

            

        // ... retrieve other details as needed
    
        $userData = [
            'id' => $userId,
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'is_gold' => $is_gold,

            
            // ... other user details
        ];
    
        return new Response($twig->render('admin/see-user.html.twig', ['user' => $userData]));
    }
    
    #[Route('/delete/{id}', methods: ['POST'])]
    public function deleteUserById(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User deleted successfully.']);
    }



    #[Route('/admin', name: 'admin_dashboard')]

    public function admin(Security $security, UserRepository $userRepository): Response
    {
        // Check if the user is authenticated
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirect the user to the login page or show an access denied message
            return $this->redirectToRoute('app_login');
        }
    
        $user = $security->getUser(); // Get the currently authenticated user
        $users = $userRepository->findAll();
        $userRepository2 = $this->getDoctrine()->getRepository(User::class);
        $users2 = $userRepository2->findAll();
    
        $userCount = $userRepository->count([]); // Get the count of all users
    
        return $this->render('admin/admin.html.twig', [
            'userCount' => $userCount,
            'user' => $user,
            'users2' => $users2,
        ]);
    }
    

    public function adminPage(): Response
    {
        $userRepository2 = $this->getDoctrine()->getRepository(User::class);
        $users2 = $userRepository2->findAll();

        return $this->render('admin/admin.html.twig', ['users2' => $users2]);
    }





    #[Route('/edit-password/{id}', name: 'edit-password')]
    public function editPassword(Request $request, int $id, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Fetch the user from the database using the provided ID
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
    
        // Check if the user exists
        if (!$user instanceof UserInterface) {
            throw $this->createNotFoundException('User not found');
        }
    
        // Instantiate the form
        $form = $this->createForm(UserPasswordType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode and set the new password for the user
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
    
            // Update the user password
            $entityManager->flush();
    
            // Redirect to the same page (reload)
            return $this->redirectToRoute('edit-password', ['id' => $id]);
        }
    
        return $this->render('user/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    



    #[Route('/edit-details/{id}', name: 'edit-details')]
    public function editProfile(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        // Fetch the user from the database using the provided ID
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
    
        // Check if user exist
        if (!$user instanceof UserInterface) {
            throw $this->createNotFoundException('User not found');
        }
    
        $form = $this->createForm(UserProfileType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the user profile
            $entityManager->flush();
    
            // (reload)
            return $this->redirectToRoute('edit-details', ['id' => $id]);
        }
    
        return $this->render('user/edit_details.html.twig', [
            'form' => $form->createView(),
        ]);
    }
        

    #[Route('/', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }


    #[Route('/signup', name: 'registration')]
    public function registration(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the user's password
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRole('ROLE_USER');

            // Handle the registration logic (e.g., persisting the user to the database)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to the login page or any other page as needed
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/assoc_signup', name: 'assoc_signup')]
    public function assoc_signup(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the user's password
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRole('ROLE_ASSOC');

            // Handle the registration logic (e.g., persisting the user to the database)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to the login page or any other page as needed
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/assoc-signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/donator_signup', name: 'donator_signup')]
    public function donatorSignup(Request $request, UserPasswordEncoderInterface $passwordEncoder, EmailService $emailService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the user's password
            $hashedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRole('ROLE_DON');

            // Handle the registration logic (e.g., persisting the user to the database)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Send signup email
            $emailService->sendSignupEmail($user->getEmail());

            // Redirect to the login page or any other page as needed
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/donator-signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function notfound(): Response
    {
        $response = $this->render('notfound.html.twig');
        $response->setStatusCode(404);

        return $response;
    }


    // #[Route(path: '/logout', name: 'app_logout')]
    // public function logout(): void
    // {
    //     throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    // }

    // #[Route(path: '/admin-login', name: 'admin_login')]
    // public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    // {
    //     // Create the login form
    //     $form = $this->createForm(LoginFormType::class);

    //     // Handle form submission
    //     $form->handleRequest($request);

    //     // Check if the form is submitted and valid
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Authentication logic can be handled here if needed
    //         // For example, redirect to a specific route upon successful login
    //         return $this->redirectToRoute('admin');
    //     }

    //     // If there's an authentication error, get it from the authentication utils
    //     $error = $authenticationUtils->getLastAuthenticationError();
    //     // Get the last username entered by the user
    //     $lastUsername = $authenticationUtils->getLastUsername();

    //     // Render the login form template with the form, last username, and error
    //     return $this->render('admin/admin-login.html.twig', [
    //         'form' => $form->createView(), // Pass the form to the template
    //         'last_username' => $lastUsername,
    //         'error' => $error,
    //     ]);
    // }
}
