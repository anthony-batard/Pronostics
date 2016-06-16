<?php

namespace Dwf\PronosticsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\ORM\Query\Parameter as Parameter;
use Doctrine\ORM\EntityRepository;
use Dwf;

/**
 * ScorerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScorerRepository extends EntityRepository
{
    public function findBestScorersByEvent(Dwf\PronosticsBundle\Entity\Event $event, $limit = false)
    {
        $qb = $this->createQueryBuilder('s')
        ->leftJoin('s.game','g')
        ->leftJoin('s.player','p')
        ->select('p.id as player_id','p.name as name','SUM(s.score) AS total')
        ->where('s.event = :event')
        ->setParameter('event', $event)
        ->andWhere('s.owngoal IS NULL OR s.owngoal = 0')        
        ->groupBy('s.player')
        ->addOrderBy('total', 'DESC')
        ->addOrderBy('name', 'ASC')
        ;
        if($limit)
            $qb->setMaxResults($limit);
    
        $query = $qb->getQuery();
        return $query->getResult();
    }
    
    public function findBestOffensesByEvent(Dwf\PronosticsBundle\Entity\Event $event, $limit = false)
    {
        if ($event->getNationalTeams()) {
            $team = 'nationalTeam';
        } else {
            $team = 'team';
        }
        $qb = $this->createQueryBuilder('s')
        ->leftJoin('s.game','g')
        ->leftJoin('s.player','p')
        ->leftJoin('p.'.$team,'t')
        ->select('t.id AS team_id', 't.name AS name','SUM(s.score) AS total')
        ->where('s.event = :event')
        ->setParameter('event', $event)
        ->andWhere('s.owngoal IS NULL OR s.owngoal = 0')
        ->groupBy('p.'.$team)
        ->addOrderBy('total', 'DESC')
        ;
        $qb->setMaxResults(10);
    
        $query = $qb->getQuery();
        return $query->getResult();
    }
    
    public function findScorersByGameAndTeam(Dwf\PronosticsBundle\Entity\Game $game, Dwf\PronosticsBundle\Entity\Team $team, $national = FALSE)
    {
        $qb = $this->createQueryBuilder('s')
        ->leftJoin('s.game','g')
        ->leftJoin('s.player','p')
        //->select('p.id as player_id','p.name as name','SUM(s.score) AS total')
        ->select('s')
        ->where('s.game = :game')
        ->setParameter('game', $game);
        if($national) {
            $qb->andWhere('p.nationalTeam = :team');
        } else {
            $qb->andWhere('p.team = :team');
        }
        $qb->setParameter('team', $team)
        ->andWhere('s.owngoal = 0')
        ->orWhere('p.team != :team AND s.game = :game AND s.owngoal = 1')
        ->setParameters(new ArrayCollection(array(new Parameter('team', $team),new Parameter('game', $game))))
        ->addOrderBy('s.score', 'DESC')
        ->addOrderBy('p.name', 'ASC')
        ;
        
        $query = $qb->getQuery();
        return $query->getResult();
    }
    
}
