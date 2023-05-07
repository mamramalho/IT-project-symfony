<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/api')]
class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $email = $decoded->email;
        $plainPassword = $decoded->plainPassword;
  
        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $em->persist($user);
        $em->flush();
        
        $responseData = [
            'success' => true,
            'message' => 'Registration successful'
        ];

        return new JsonResponse($responseData);

        
    }

    #[Route('/verify/email', name: 'app_verify_email', methods: ['GET'])]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_admin');
    }
}
