<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

	/**
	 * @param int $nb
	 *
	 * @return Product[]
	 */
	public function getLast(int $nb): array {
		return $this->createQueryBuilder('p')
		            ->orderBy('p.id', 'DESC')
		            ->setMaxResults($nb)
		            ->getQuery()
		            ->getResult()
			;
	}

	/**
	 * @param int[] $ids
	 *
	 * @return Product[]
	 */
	public function getProductsFromIds(array $ids): array {
		if (!count($ids)) {
			return [];
		}
		return $this->findBy(['id' => $ids]);
	}
}
