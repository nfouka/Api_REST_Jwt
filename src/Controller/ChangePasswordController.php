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
use App\Form\ChangePasswordType;
use App\Entity\User;
use App\Event\EmailChangePasswordEvent;

class ChangePasswordController extends AbstractFOSRestController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route(path="api/change", name="change_password", methods="POST")
     */
    public function changePassword(Request $request, EventDispatcherInterface $eventDispatcher): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $request->request->get('email');
            $password = $form->getData()->getPassword();
            $passwordNew = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $em = $this->getDoctrine()->getManager();
            $userRepository = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            $userRepository->setPassword($passwordNew);

            $event = new EmailChangePasswordEvent($userRepository);
			$eventDispatcher->dispatch($event, EmailChangePasswordEvent::NAME,);

            $em->persist($userRepository);
            $em->flush();

            return new JsonResponse(['status' => 'ok']);
        }

        throw new HttpException(400, "Invalid data");
    }
}
