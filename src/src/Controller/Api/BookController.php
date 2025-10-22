<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/books')]
final class BookController extends AbstractController
{
    #[Route('/list', name: 'api_book_list', methods: ['GET'])]
    public function getList(Request $request, BookRepository $bookRepository, PaginatorInterface $pagination): Response
    {
        $pagination = $pagination->paginate(
            $bookRepository->createFindAllQuery(),
            $request->query->getInt('page', 1),
            100
        );

        return $this->json($pagination->getItems());
    }

    #[Route('/by-id/{id}', name: 'api_book_by_id', methods: ['GET'])]
    public function getById(?Book $book): Response
    {
        if (null === $book) {
            return $this->json(['error' => 'Book not found']);
        }

        return $this->json($book);
    }

    #[Route('/update/{id}', name: 'api_book_update', methods: ['POST'])]
    public function update(?Book $book, Request $request, EntityManagerInterface $em): Response
    {
        if (null === $book) {
            return $this->json(['error' => 'Book not found']);
        }

        $form = $this->createForm(BookType::class);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json(['errors' => $form->getErrors()]);
        }

        /** @var Book $newBook */
        $this->updateBook($book, $form->getData());

        $em->flush();

        return $this->json($book);
    }

    #[Route('/id/{id}', name: 'api_book_delete', methods: ['DELETE'])]
    public function deleteById(?Book $book, EntityManagerInterface $em): Response
    {
        if (null === $book) {
            return $this->json(['error' => 'Book not found']);
        }

        $em->remove($book);
        $em->flush();

        return $this->json([]);
    }

    private function updateBook(Book $book, Book $newBook)
    {
        if ($newBook->getTitle()) {
            $book->setTitle($newBook->getTitle());
        }

        if ($newBook->getDescription()) {
            $book->setDescription($newBook->getDescription());
        }
    }
}
