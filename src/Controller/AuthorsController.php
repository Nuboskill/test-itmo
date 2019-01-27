<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class AuthorsController extends AbstractController
{
    /**
     * @Route("/authors", name="authors", methods={"GET"})
     */
    public function index()
    {
        // Авторы с книгами
        $authors = $this->getDoctrine()
            ->getRepository(Author::class)
            ->findOneToManyJoinedToBooks();

        return $this->render('authors/index.html.twig', compact('authors'));
    }

    /**
     * @Route("/authors/{id<\d+>}", name="authors_show", methods={"GET"})
     * @Entity("author", expr="repository.findOneToManyJoinedToBooksBy(id)")
     */
    public function show(Author $author)
    {
        return $this->render('authors/show.html.twig', compact('author'));
    }

    /**
     * @Route("/authors/new", name="authors_new", methods={"POST", "GET"})
     */
    public function new(Request $request)
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
     * @Route("/authors/edit/{id<\d+>}", name="authors_edit", methods={"POST", "GET"})
     */
    public function update(Request $request, Author $author)
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
     * @Route("/authors/delete/{id<\d+>}", name="authors_delete", methods={"DELETE"})
     */
    public function delete(Author $author)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($author);
        $entityManager->flush();

        return new Response($this->generateUrl('authors'));
    }

}
