<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Form\ForgotPasswordType;
use App\Entity\User;
use App\Utils\PasswordGenerator;
use App\Event\EmailForgotPasswordEvent;

class ForgotPasswordController extends AbstractFOSRestController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route(path="api/forgot", name="forgot_password", methods="POST")
     */
    public function forgotPassword(Request $request, EventDispatcherInterface $eventDispatcher): JsonResponse
    {
        $user = new User();
        $passwordGenerator = new PasswordGenerator();
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $email = $request->request->get('email');
            $em = $this->getDoctrine()->getManager();
            $userRepository = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            $generatePassword = $this->passwordEncoder->encodePassword($user, $passwordGenerator->generatePassword());
            $userRepository->setPassword($generatePassword);

			$event = new EmailForgotPasswordEvent($userRepository);
			$eventDispatcher->dispatch($event, EmailForgotPasswordEvent::NAME);

			$em->persist($userRepository);
            $em->flush();

            return new JsonResponse(['status' => 'ok']);
        }

        throw new HttpException(400, "Invalid data");
    }
}
