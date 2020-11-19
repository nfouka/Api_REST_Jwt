<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class HelloController extends AbstractFOSRestController
{
	/**
	 * @Route("/", name="hello", methods="GET")
     */
    public function index(): Response
    {
        return new JsonResponse([
            'hello' => 'This is a simple example of resource returned by your APIs'
        ]);
    }
}
