<?php

namespace App\Repository;

use App\Entity\OrderLine;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderLine[]    findAll()
 * @method OrderLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderLineRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderLine::class);
    }
}
