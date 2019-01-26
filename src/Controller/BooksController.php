<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class BooksController extends AbstractController
{
    /**
     * @Route("/books", name="books", methods={"GET"})
     */
    public function index()
    {
        // Книги с авторами
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findManyJoinedToAuthors();

        return $this->render('books/index.html.twig', compact('books'));
    }

    /**
     * @Route("/books/{id<\d+>}", name="books_show", methods={"GET"})
     */
    public function show(Book $book)
    {
        return $this->render('books/show.html.twig', compact('book'));
    }

    /**
     * @Route("/books/new", name="books_new", methods={"POST", "GET"})
     */
    public function new(Request $request)
    {
        $book = new Book();

        $form = $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('books_show', ['id' => $book->getId()]);
        }

        return $this->render(
            'books/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/books/edit/{id<\d+>}", name="books_edit", methods={"POST", "GET"})
     */
    public function update(Request $request, Book $book)
    {
        $form = $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render(
            'books/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/books/delete/{id<\d+>}", name="books_delete", methods={"DELETE"})
     */
    public function delete(Book $book)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($book);
        $entityManager->flush();

        return new Response($this->generateUrl('books'));
    }

}
