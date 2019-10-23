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

class AuthorsController extends AbstractController
{
    /** @var AuthorRepository */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @Route("/authors", name="authors", methods={"GET"})
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
     * @Route("/authors/{id<\d+>}", name="authors_show", methods={"GET"})
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
     * @Route("/authors/new", name="authors_new", methods={"POST", "GET"})
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
     * @Route("/authors/edit/{id<\d+>}", name="authors_edit", methods={"POST", "GET"})
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
     * @Route("/authors/delete/{id<\d+>}", name="authors_delete", methods={"DELETE"})
     */
    public function delete(Author $author): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($author);
        $entityManager->flush();

        return new Response($this->generateUrl('authors'));
    }
}
