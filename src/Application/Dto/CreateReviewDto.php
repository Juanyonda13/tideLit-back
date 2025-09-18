<?php
namespace App\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateReviewDto
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $book_id;

    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 5)]
    public int $rating;

    #[Assert\NotBlank]
    public string $comment;
}