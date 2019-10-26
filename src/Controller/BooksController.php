<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/books", name="books_")
 */
class BooksController extends AbstractController
{
    /** @var BookRepository */
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @return Response
     *
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $books = $this->bookRepository->findOneToManyJoinedToAuthors();

        return $this->render('books/index.html.twig', compact('books'));
    }

    /**
     * @param Book $book
     *
     * @return Response
     *
     * @Route("/{id<\d+>}", name="show", methods={"GET"})
     * @Entity("book", expr="repository.findOneToManyJoinedToAuthorsBy(id)")
     */
    public function show(Book $book): Response
    {
        return $this->render('books/show.html.twig', compact('book'));
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/new", name="new", methods={"POST", "GET"})
     */
    public function new(Request $request): Response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
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
     * @param Request $request
     * @param Book $book
     *
     * @return Response
     *
     * @Route("/edit/{id<\d+>}", name="edit", methods={"POST", "GET"})
     * @Entity("book", expr="repository.findOneToManyJoinedToAuthorsBy(id)")
     */
    public function update(Request $request, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('notice',"Книга успешно изменена.");

            return $this->redirect($request->getUri());
        }

        return $this->render(
            'books/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Book $book
     *
     * @return Response
     *
     * @Route("/delete/{id<\d+>}", name="delete", methods={"DELETE"})
     */
    public function delete(Book $book): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($book);
        $entityManager->flush();

        return new Response($this->generateUrl('books_index'));
    }
}
