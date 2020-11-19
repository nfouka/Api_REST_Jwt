<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use App\Event\EmailChangePasswordEvent;

class MailChangePasswordListener
{
    protected $twig;

    protected $mailer;

    public function __construct(\Twig\Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function onMailChangePasswordEvent(EmailChangePasswordEvent $event): void
    {
        $user = $event->getUser();
        $name = $event->getUser()->getName();
        $email = $event->getUser()->getEmail();
        $password = $event->getUser()->getPassword();

        $body = $this->renderTemplate($name, $password, $email);

        $message = (new \Swift_Message('Change Password Successfully!'))
            ->setFrom($email)
            ->setTo($email)
			->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }

    protected function renderTemplate($name, $password, $email): string
    {
		return $this->twig->render(
            'emails/changePassword.html.twig',
            [
                'name' => $name,
                'password' => $password,
				'email' => $email
            ]
        );
    }
}
