<?php

namespace Dwf\PronosticsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Dwf;

/**
 * GameTypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameTypeRepository extends EntityRepository
{
	public function findAllByEvent(Dwf\PronosticsBundle\Entity\Event $event)
	{
		$qb = $this->createQueryBuilder('g')
			->join('g.events', 'eg')
			//->where($qb->expr()->in('eg.event', array($event)))
			
			->where('eg.id IN (:event)')
			->setParameter('event', $event)
		;
	
		$query = $qb->getQuery();
		return $query->getResult();
	}
}
