<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\FoodCollectionProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class FoodImportController extends AbstractController
{
    private FoodCollectionProcessor $foodCollectionProcessor;

    private string $filePath;

    public function __construct(FoodCollectionProcessor $foodCollectionProcessor, string $filePath)
    {
        $this->foodCollectionProcessor = $foodCollectionProcessor;
        $this->filePath = $filePath;
    }

    #[Route('/api/import-food', name: 'import_food', methods: ['GET'])]
    public function importFood(): Response
    {
        try {
            $this->foodCollectionProcessor->process($this->filePath);

            return $this->json([
                'status' => 'success',
                'message' => 'Food collections processed and stored.',
            ], Response::HTTP_OK);

        } catch (FileNotFoundException $th) {
            return $this->json([
                'status' => 'error',
                'message' => 'File not found.',
            ], Response::HTTP_NOT_FOUND);
  
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while importing the food.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
