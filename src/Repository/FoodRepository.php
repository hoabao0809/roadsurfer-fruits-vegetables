<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Food;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FoodRepository extends ServiceEntityRepository implements FoodRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Food::class);
    }

    public function add(Food $food): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($food);
        $entityManager->flush();
    }

    public function addMany(array $foodEntities): void
    {
        if (empty($foodEntities)) {
            return;
        }

        $entityManager = $this->getEntityManager();

        foreach ($foodEntities as $entity) {
            $entityManager->persist($entity);
        }

        $entityManager->flush();
    }
}
