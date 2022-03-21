<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllCategories(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Category c
            ORDER BY c.id ASC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findById(int $id) {

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT p
            FROM App\Entity\Category p
            WHERE p.id = :id'
        )->setParameter('id', $id)->getOneOrNullResult();

        // returns an array of Product objects
        return $query;
    }
}
