<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use App\Event\EmailRegistrationUserEvent;

class MailRegistrationUserListener
{
    protected $twig;

    protected $mailer;

    public function __construct(\Twig\Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function onMailRegistrationUserEvent(EmailRegistrationUserEvent $event): void
    {
        $user = $event->getUser();
        $email = $event->getUser()->getEmail();
        $password = $event->getUser()->getPassword();
        $name = $event->getUser()->getName();

        $body = $this->renderTemplate($name, $email);

		$message = (new \Swift_Message('Registration User Successfully!'))
            ->setFrom($email)
            ->setTo($email)
            ->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }

    protected function renderTemplate($name, $email): string
    {
		return $this->twig->render(
            'emails/registration.html.twig',
            [
                'name' => $name,
				'email' => $email
            ]
        );
    }

}
