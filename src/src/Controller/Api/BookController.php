<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Repository\BookRepository;
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

        return $this->json($pagination->getItems(), context: ['groups' => ['api:read']]);
    }

    #[Route('/by-id/{id}', name: 'api_book_by_id', methods: ['GET'])]
    public function getById(Book $book): Response
    {
        return $this->json($book, context: ['groups' => ['api:read']]);
    }
}
