<?php
namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) { parent::__construct($registry, Book::class); }

    /** @return array<int, array{title:string,author:string,published_year:int,average_rating:float|null}> */
    public function listWithAverageRating(): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.title AS title, b.author AS author, b.publishedYear AS published_year, AVG(r.rating) AS average_rating')
            ->leftJoin('b.reviews', 'r')
            ->groupBy('b.id')
            ->orderBy('b.title', 'ASC');

        return array_map(function ($row) {
            $row['average_rating'] = $row['average_rating'] !== null ? (float) number_format((float)$row['average_rating'], 2, '.', '') : null;
            return $row;
        }, $qb->getQuery()->getArrayResult());
    }
}