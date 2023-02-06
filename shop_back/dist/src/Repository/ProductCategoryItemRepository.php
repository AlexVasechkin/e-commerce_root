<?php

namespace App\Repository;

use App\Entity\ProductCategoryItem;
use App\Message\IndexProductMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @extends ServiceEntityRepository<ProductCategoryItem>
 *
 * @method ProductCategoryItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCategoryItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCategoryItem[]    findAll()
 * @method ProductCategoryItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCategoryItemRepository extends ServiceEntityRepository
{
    private MessageBusInterface $messageBus;

    public function __construct(
        ManagerRegistry $registry,
        MessageBusInterface $messageBus
    ) {
        parent::__construct($registry, ProductCategoryItem::class);
        $this->messageBus = $messageBus;
    }

    public function save(ProductCategoryItem $entity, bool $flush = false): ProductCategoryItem
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        $this->messageBus->dispatch(new IndexProductMessage($entity->getProduct()->getId()));

        return $entity;
    }

    public function remove(ProductCategoryItem $entity, bool $flush = false): void
    {
        $productId = $entity->getProduct()->getId();

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        $this->messageBus->dispatch(new IndexProductMessage($productId));
    }
}
