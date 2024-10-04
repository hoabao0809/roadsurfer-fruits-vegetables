<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Exception\ValidationException;
use App\Service\FoodServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FoodController extends AbstractController
{
    private FoodServiceInterface $foodService;

    public function __construct(FoodServiceInterface $foodService)
    {
        $this->foodService = $foodService;
    }

    /**
     * Retrieve all foods, optionally converting units.
     */
    #[Route('/api/foods', name: 'index_foods', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $targetUnit = $request->query->get('unit', null);

        try {
            $foods = $this->foodService->getAllFoods($targetUnit);

            return $this->json([
                'status' => 'success',
                'message' => 'Foods retrieved successfully.',
                'data' => $foods,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while retrieving foods.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Add a new food.
     */
    #[Route('/api/foods', name: 'add_food', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $food = $this->foodService->createFood($data);

            return $this->json([
                'status' => 'success',
                'message' => 'Food created successfully.',
                'data' => $food,
            ], Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while creating the food.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Search for foods based on criteria.
     */
    #[Route('/api/foods/search', name: 'search_foods', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $conditions = $request->query->all();

        try {
            $result = $this->foodService->searchFoods($conditions);

            if (empty($result)) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'No foods found matching the criteria.',
                    'data' => [],
                ], Response::HTTP_NOT_FOUND);
            }

            return $this->json([
                'status' => 'success',
                'message' => 'Foods retrieved successfully.',
                'data' => $result,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while searching for foods.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
