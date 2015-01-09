<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DocumentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocumentRepository extends EntityRepository
{
	public function myFindByCategoryTagAndProfile($tagCategory, $idProfile)
	{
		$qb = $this->createQueryBuilder('document');
		$qb->join('document.category', 'category', 'WITH', 'category.tag = :tag')
			->setParameter('tag', $tagCategory)
			->join('category.profile', 'profile', 'WITH', 'profile.id = :id')
			->setParameter('id', $idProfile);
		
		$results = $qb->getQuery()->getResult();
		return $results[0];
	}
}
