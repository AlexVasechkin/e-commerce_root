<?php

namespace App\Repository;

use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCategory>
 *
 * @method ProductCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCategory[]    findAll()
 * @method ProductCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategory::class);
    }

    public function save(ProductCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function fetchAll(): array
    {
        $query = implode(PHP_EOL, [
            'select *',
            '  from product_category as t',
            '  order by',
            '     t.id',
            ';',
        ]);

        return $this->getEntityManager()->getConnection()
            ->fetchAllAssociative($query)
        ;
    }

    /**
     * @return ProductCategory[]
     */
    public function fetchModelById(array $productCategoryIdList): array
    {
        if ($productCategoryIdList === []) {
            return [];
        }

        return $this
            ->createQueryBuilder('pc')
            ->where('pc.id IN (:ids)')
            ->setParameter('ids', $productCategoryIdList)
            ->getQuery()
            ->getResult()
        ;
    }
}
