<?php

namespace MyWebsite\WebBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MenuRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MenuRepository extends EntityRepository
{
	public function myFindMenusByDisplay($arrayDisplay)
	{
		$qb = $this->createQueryBuilder('menu')
			->where('menu.isRoot = :menu_isRoot')
			->setParameter('menu_isRoot', true)
			->andWhere('menu.active = :menu_active')
			->setParameter('menu_active', true)
			->leftJoin('menu.module', 'module', 'WITH', 'module.active = :module_active')
			->setParameter('module_active', true)
			->addSelect('module')
			->leftJoin('module.bundle', 'bundle', 'WITH', 'bundle.active = :bundle_active')
			->setParameter('bundle_active', true)
			->addSelect('bundle')
			->leftJoin('menu.subMenus', 'subMenu')
			->addSelect('subMenu')
			->addOrderBy('menu.priority', 'ASC')
		;
		
		if(count($arrayDisplay) > 0)
		{
			$strParam = '';
			foreach($arrayDisplay as $key => $display)
			{
				$strParam = ($key == 0) 
					? $strParam = $strParam.'menu.display = :menu_display'.($key) 
					: $strParam = $strParam.' OR menu.display = :menu_display'.($key)
				;
			}
			
			$qb = $qb->andWhere($strParam);
			foreach($arrayDisplay as $key => $display)
			{
				$qb = $qb->setParameter('menu_display'.($key), $display);
			}
		}
		
		$results = $qb->getQuery()->getResult();
		
		return $results;
	}
}
