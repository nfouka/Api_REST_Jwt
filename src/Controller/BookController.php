<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Entity\Book;
use App\Form\BookType;

/**
 * @Route("/api/books")
 */
class BookController extends AbstractFOSRestController
{
	/**
	 * @Route("/", name="book_index", methods="GET")
     */
    public function index(): array
	{
		$em = $this->getDoctrine()->getManager();
		$books = $em->getRepository(Book::class)->findAll();

        if (!$books) {
			throw new HttpException(400, "Invalid data");
		}

		return $books;
	}

	/**
	 * @Route("/{id}", name="book_new", methods="GET")
     */
    public function show(int $id): ?Book
    {
        if (!$id) {
            throw new HttpException(400, "Invalid id");
        }

		$em = $this->getDoctrine()->getManager();
		$book = $em->getRepository(Book::class)->find($id);

        if (!$book) {
			throw new HttpException(400, "Invalid data");
		}

		return $book;
	}

	/**
	 * @Route("/new", name="post_book", methods="POST")
     */
    public function new(Request $request): ?Book
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $book;
        }

        throw new HttpException(400, "Invalid data");
    }

	/**
	 * @Route("/edit/{id}", name="put_book", methods="PUT")
	 */
    public function edit(Request $request, int $id): ?Book
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository(Book::class)->find($id);
        $form = $this->createForm(BookType::class, $book, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($book);
            $em->flush();

            return $book;
        }

        throw new HttpException(400, "Invalid data");
    }

	/**
	 * @Route("/remove/{id}", name="delete_book", methods="DELETE")
	 */
    public function delete(int $id): ?Book
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository(Book::class)->find($id);
        $em->remove($book);
        $em->flush();

        if (!$id) {
            throw new HttpException(400, "Invalid id");
        }

        return $book;
    }
}

