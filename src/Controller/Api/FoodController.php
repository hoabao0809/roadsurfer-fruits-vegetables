<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\FoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FoodController extends AbstractController
{
    private FoodRepository $foodRepository;
    private ValidatorInterface $validator;

      public function __construct(FoodRepository $foodRepository, ValidatorInterface $validator)
    {
        $this->foodRepository = $foodRepository;
        $this->validator = $validator;
    }

    #[Route('/api/foods', name: 'index_foods', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $foods = $this->foodRepository->findAll();
        return $this->json($foods);
    }
}
