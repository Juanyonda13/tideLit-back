<?php
namespace App\Infrastructure\Controller;

use App\Application\Dto\CreateReviewDto;
use App\Application\Service\CreateReviewHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReviewsPostController
{
    public function __construct(
        private CreateReviewHandler $handler,
        private ValidatorInterface $validator
    ) {}

    #[Route('/api/reviews', name: 'api_reviews_create', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true) ?? [];
        $dto = new CreateReviewDto();
        $dto->book_id = (int)($payload['book_id'] ?? 0);
        $dto->rating  = (int)($payload['rating']  ?? 0);
        $dto->comment = (string)($payload['comment'] ?? '');

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $msgs = [];
            foreach ($errors as $e) { $msgs[] = $e->getPropertyPath().': '.$e->getMessage(); }
            return new JsonResponse(['errors' => $msgs], 400);
        }

        $review = ($this->handler)($dto);

        return new JsonResponse([
            'id'         => $review->getId(),
            'created_at' => $review->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ], 201);
    }
}
