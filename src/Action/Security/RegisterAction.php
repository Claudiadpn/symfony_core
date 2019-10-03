<?php

declare(strict_types=1);

namespace App\Action\Security;

use App\Entity\User;
use App\Form\Dto\Security\Registration;
use App\Form\Type\Security\RegisterType;
use App\Security\RegistrationHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class RegisterAction
{
    private $twig;

    private $formFactory;

    private $router;

    private $mailer;

    private $registrationHandler;

    public function __construct(
        Environment $twig,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        RegistrationHandler $registrationHandler,
        \Swift_Mailer $mailer
    ) {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->registrationHandler = $registrationHandler;
        $this->mailer = $mailer;
    }

    public function __invoke(Request $request, Session $session)
    {
        $registerForm = $this->formFactory->create(RegisterType::class);

        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            /** @var Registration $data */
            $registration = $registerForm->getData();

            $user = $this->registrationHandler->handleRegistration($registration);

            $this->sendConfirmationEmail($user);

            $session->getFlashBag()->add('success', sprintf('You are now registered. Please check your mailbox "%s" to confirm your email address.', $registration->email));

            return new RedirectResponse($this->router->generate('homepage'));
        }

        return new Response(
            $this->twig->render('security/register.html.twig', [
                'registerForm' => $registerForm->createView(),
            ])
        );
    }

    private function sendConfirmationEmail(User $user)
    {
        $message = (new \Swift_Message('Registration confirmation'))
            ->setFrom('noreply@symfony_core.local')
            ->setTo($user->getEmail())
            ->setBody($this->twig->render('emails/security/registration.html.twig', ['user' => $user]), 'text/html');

        $this->mailer->send($message);
    }
}
