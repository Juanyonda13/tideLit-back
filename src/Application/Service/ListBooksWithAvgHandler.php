<?php
namespace App\Application\Service;

use App\Infrastructure\Persistence\Doctrine\Repository\BookRepository;

class ListBooksWithAvgHandler
{
    public function __construct(private BookRepository $books) {}

    public function __invoke(): array
    {
        return $this->books->listWithAverageRating();
    }
}