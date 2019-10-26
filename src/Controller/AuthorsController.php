<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/authors", name="authors_")
 */
class AuthorsController extends AbstractController
{
    /** @var AuthorRepository */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $authors = $this->authorRepository->findOneToManyJoinedToBooks();

        return $this->render('authors/index.html.twig', compact('authors'));
    }

    /**
     * @param Author $author
     *
     * @return Response
     *
     * @Route("/{id<\d+>}", name="show", methods={"GET"})
     * @Entity("author", expr="repository.findOneToManyJoinedToBooksBy(id)")
     */
    public function show(Author $author): Response
    {
        return $this->render('authors/show.html.twig', compact('author'));
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
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('authors_show', ['id' => $author->getId()]);
        }

        return $this->render(
            'authors/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Author $author
     *
     * @return Response
     *
     * @Route("/edit/{id<\d+>}", name="edit", methods={"POST", "GET"})
     */
    public function update(Request $request, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('notice','Автор успешно изменен.');

            return $this->redirect($request->getUri());
        }

        return $this->render(
            'authors/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Author $author
     *
     * @return Response
     *
     * @Route("/delete/{id<\d+>}", name="delete", methods={"DELETE"})
     */
    public function delete(Author $author): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($author);
        $entityManager->flush();

        return new Response($this->generateUrl('authors_index'));
    }
}
