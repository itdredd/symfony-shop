<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findAllProducts(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            ORDER BY p.id ASC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findById(int $id) {

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            WHERE p.id = :id'
        )->setParameter('id', $id)->getOneOrNullResult();

        // returns an array of Product objects
        return $query;
    }

    public function findByCategoryId(int $id) {

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            WHERE p.category = :category_id'
        )->setParameter('category_id', $id)->getResult();

        // returns an array of Product objects
        return $query;
    }


}
