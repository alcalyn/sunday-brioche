<?php

namespace Brioche\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BriocheRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BriocheRepository extends EntityRepository
{
    private function findFull()
    {
        return $this->_em->createQueryBuilder()
                ->select('b, r, s, e, cl, ci, m, co, ct')
                ->from('BriocheCoreBundle:Brioche', 'b')
                ->leftJoin('b.round', 'r')
                ->leftJoin('b.size', 's')
                ->leftJoin('b.extra', 'e')
                ->leftJoin('b.client', 'cl')
                ->leftJoin('cl.city', 'ci')
                ->leftJoin('b.messages', 'm')
                ->leftJoin('b.code', 'co')
                ->leftJoin('co.codeType', 'ct')
                ->orderBy('m.dateCreate')
        ;
    }
    
    public function findFullById($id)
    {
        $q = $this->findFull()
                ->where('b.id = :id')
                ->setParameter(':id', $id)
        ;
        
        return $q
                ->getQuery()
                ->getOneOrNullResult()
        ;
    }
    
    public function findFullByToken($token)
    {
        $q = $this->findFull()
                ->where('b.token = :token')
                ->setParameter(':token', $token)
        ;
        
        return $q
                ->getQuery()
                ->getOneOrNullResult()
        ;
    }
}
