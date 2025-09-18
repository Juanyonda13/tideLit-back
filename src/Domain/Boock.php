<?php
namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: \App\Infrastructure\Persistence\Doctrine\Repository\BookRepository::class)]
class Book
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $author;

    #[ORM\Column(type: 'integer')]
    private int $publishedYear;

    /** @var Collection<int, Review> */
    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Review::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $reviews;

    public function __construct(string $title, string $author, int $publishedYear)
    {
        $this->title = $title;
        $this->author = $author;
        $this->publishedYear = $publishedYear;
        $this->reviews = new ArrayCollection();
    }

    // getters...
    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getAuthor(): string { return $this->author; }
    public function getPublishedYear(): int { return $this->publishedYear; }
    /** @return Collection<int, Review> */
    public function getReviews(): Collection { return $this->reviews; }
}
