<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Util\Json;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

#[Route('/api')]
class RegistrationController extends AbstractController
{

    private $verifyEmailHelper;
    private $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
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


        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'registration_confirmation_route',
            $user->getId(),
            $user->getEmail()
        );

        $verificationEmail = new TemplatedEmail();
        $verificationEmail->from('elitecar.verify.email@gmail.com');
        $verificationEmail->to($user->getEmail());
        $verificationEmail->htmlTemplate('registration/confirmation_email.html.twig');
        $verificationEmail->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

        $this->mailer->send($verificationEmail);
        
        $responseData = [
            'success' => true,
            'message' => 'Registration successful'
        ];

        return new JsonResponse($responseData);
    }

    #[Route('/verify', name: 'registration_confirmation_route')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        // Mark your user as verified. e.g. switch a User::verified property to true

        $this->addFlash('success', 'Your e-mail address has been verified.');

        return new Response();
    }

}
