<?php
namespace App\Application\Service;

use App\Application\Dto\CreateReviewDto;
use App\Domain\Review;
use App\Infrastructure\Persistence\Doctrine\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateReviewHandler
{
    public function __construct(
        private BookRepository $books,
        private EntityManagerInterface $em
    ) {}

    public function __invoke(CreateReviewDto $dto): Review
    {
        $book = $this->books->find($dto->book_id);
        if (!$book) {
            throw new BadRequestHttpException('book_id no existe');
        }

        $review = new Review($book, $dto->rating, $dto->comment);
        $this->em->persist($review);
        $this->em->flush();

        return $review;
    }
}
