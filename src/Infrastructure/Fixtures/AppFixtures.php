<?php
namespace App\Infrastructure\Fixtures;

use App\Domain\Book;
use App\Domain\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $em): void
    {
        $books = [
            ['El Arte de Programar', 'Donald Knuth', 1968],
            ['Clean Code', 'Robert C. Martin', 2008],
            ['Refactoring', 'Martin Fowler', 1999],
        ];

        $bookEntities = [];
        foreach ($books as [$title,$author,$year]) {
            $b = new Book($title, $author, $year);
            $em->persist($b);
            $bookEntities[] = $b;
        }
        $em->flush();

        $seed = [
            [5,'Excelente libro'], [4,'Muy útil'], [3,'Bien'], [2,'Regular'], [5,'Top'], [1,'No me gustó']
        ];

        // Distribuye 6+ reseñas entre los 3 libros
        $i = 0;
        foreach ($seed as [$rating,$comment]) {
            $em->persist(new Review($bookEntities[$i % 3], $rating, $comment));
            $i++;
        }
        $em->flush();
    }
}
