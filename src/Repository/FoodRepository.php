<?php
declare(strict_types=1);

namespace App\Repository;

use App\Dto\SearchFoodCriteria;
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

    public function search(SearchFoodCriteria $criteria): array
    {
        $qb = $this->createQueryBuilder('f');

        if ($criteria->getName()) {
            $qb->andWhere('f.name LIKE :name')
                ->setParameter('name', '%' . $criteria->getName() . '%');
        }

        if ($criteria->getType()) {
            $qb->andWhere('f.type = :type')
                ->setParameter('type', $criteria->getType());
        }

        if ($criteria->getMinQuantity()) {
            $qb->andWhere('f.quantity >= :minQuantity')
                ->setParameter('minQuantity', $criteria->getMinQuantity());
        }

        if ($criteria->getMaxQuantity()) {
            $qb->andWhere('f.quantity <= :maxQuantity')
                ->setParameter('maxQuantity', $criteria->getMaxQuantity());
        }

        if ($criteria->getUnit()) {
            $qb->andWhere('f.unit = :unit')
                ->setParameter('unit', $criteria->getUnit());
        }

        return $qb->getQuery()->getResult();
    }
}
