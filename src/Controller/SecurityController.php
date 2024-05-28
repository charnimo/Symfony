<?php
namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Form\LoginType;
use App\Security\UsersAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/signin', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {
        $user = new Users();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);
        $loginForm = $this->createForm(LoginType::class);

        $registrationForm->handleRequest($request);
        $loginForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            // Encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $registrationForm->get('plainPassword')->getData()
                )
            );

            if ($user->getUsername() === 'admin') {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            // Log in the user
            return $security->login($user, UsersAuthenticator::class, 'main');
        }

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            $formData = $loginForm->getData();
            $username = $formData['_username'];
            $password = $formData['_password'];

            $user = $entityManager->getRepository(Users::class)->findOneBy(['username' => $username]);

            if ($user && $userPasswordHasher->isPasswordValid($user, $password)) {
                // Set session variables
                $session = $request->getSession();
                $session->set('user_first_name', $user->getUsername());
                $session->set('user_id', $user->getId());

                // Log in the user
                $security->login($user, UsersAuthenticator::class, 'main');

                // Handle remember me
                $rememberMe = $request->request->get('_remember_me');
                if ($rememberMe) {
                    $token = $this->container->get('security.token_storage')->getToken();
                    $this->container->get('security.token_storage')->setToken($token);

                    $response = new Response();
                    $rememberMeService = $this->container->get('security.authentication.rememberme.services.persistent.remember_me');
                    $rememberMeService->loginSuccess($request, $response, $token);
                }
                $this->addFlash('success', 'Welcome to Carya, Dear ' . $user->getUsername());
                return $this->redirectToRoute('app_home');
            } else {
                // Handle invalid credentials
                $this->addFlash('error', 'Invalid username or password.');
            }
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/index.html.twig', [
            'registrationForm' => $registrationForm->createView(),
            'loginForm' => $loginForm->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
