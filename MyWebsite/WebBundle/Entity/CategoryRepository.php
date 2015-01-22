<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
	public function myFindWithContents($idCategory)
	{
		$qb = $this->createQueryBuilder('category')
			->where('category.id = :id')
			->setParameter('id', $idCategory)
			->leftJoin('category.contents', 'content')
			->addSelect('content')
		;
		
		$results = $qb->getQuery()->getResult();
		if($results == null)
		{
			return null;
		}
		
		return $results[0];
	}
	
	public function myFindWithDocuments($idCategory)
	{
		$qb = $this->createQueryBuilder('category')
			->where('category.id = :id')
			->setParameter('id', $idCategory)
			->leftJoin('category.documents', 'document')
			->addSelect('document')
		;
		
		$results = $qb->getQuery()->getResult();
		if($results == null)
		{
			return null;
		}
		
		return $results[0];
	}
}
