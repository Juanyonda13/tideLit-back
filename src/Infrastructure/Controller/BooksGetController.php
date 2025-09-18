<?php
namespace App\Infrastructure\Controller;

use App\Application\Service\ListBooksWithAvgHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BooksGetController
{
    public function __construct(private ListBooksWithAvgHandler $handler) {}

    #[Route('/api/books', name: 'api_books_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse($this->handler->__invoke(), 200);
    }
}
