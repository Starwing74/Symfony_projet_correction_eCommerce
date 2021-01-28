<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;

abstract class AbstractRepository extends ServiceEntityRepository {

	public function save(AbstractEntity $entity): void {
		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();
	}

	public function delete(AbstractEntity $entity): void {
		if (!$entity) {
			throw new EntityNotFoundException('Entity not found');
		}
		$this->getEntityManager()->remove($entity);
		$this->getEntityManager()->flush();
	}
}
