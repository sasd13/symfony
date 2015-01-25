<?php

namespace MyWebsite\ProfileBundle\Entity;

use Doctrine\ORM\EntityRepository;
use MyWebsite\ProfileBundle\Services\Data;

/**
 * ClientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientRepository extends EntityRepository
{
	public function myFindWithCategoriesAndContents($idClient)
	{
		$qb = $this->createQueryBuilder('client')
			->where('client.id = :id')
			->setParameter('id', $idClient)
			->leftJoin('client.categories', 'category', 'WITH', 'category.type = :type')
			->setParameter('type', 'content')
			->addSelect('category')
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
	
	public function myFindWithCategoryAndPicture($idClient)
	{
		$qb = $this->createQueryBuilder('client')
			->where('client.id = :id')
			->setParameter('id', $idClient)
			->leftJoin('client.categories', 'category', 'WITH', 'category.tag = :tag')
			->setParameter('tag', Data::CLIENT_CATEGORY_TAG_PICTURE)
			->addSelect('category')
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
