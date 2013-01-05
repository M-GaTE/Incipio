<?php

namespace mgate\PersonneBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonneRepository extends EntityRepository
{
    public function getMembreOnly()
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('n')->from('mgatePersonneBundle:Personne', 'n')
          ->where('n.membre IS NOT NULL')
          //->where( $qb->expr()->neq('n.membre', null ))
          ;
        return $query;
    }
    
    public function getEmployeOnly($prospect = null)
    {
        
        $qb = $this->_em->createQueryBuilder();
        
        if($prospect != null)
        {
            $query = $qb->select('p')
                        ->from('mgatePersonneBundle:Personne', 'p')
                        ->leftJoin('p.employe', 'e')
                        ->addSelect('e')
                        ->where('p.employe IS NOT NULL')
                        ->andWhere('e.prospect = :prospect')
                            ->setParameter('prospect', $prospect);
        }
        else
        {
            $query = $qb->select('n')->from('mgatePersonneBundle:Personne', 'n')
                        ->where('n.employe IS NOT NULL');
            
        }
          
                    
                    
        return $query;
    }
    
    public function getNotUser()
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('n')->from('mgatePersonneBundle:Personne', 'n')
          ->where('n.user IS NULL');
        return $query;
    }
}