<?php

namespace Brioche\CoreBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Brioche\CoreBundle\Entity\IPN;
use Brioche\CoreBundle\Entity\Brioche;

class BriocheIPNService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @param IPN $ipn
     */
    public function processIPN(IPN $ipn)
    {
        if ($ipn->getState() === IPN::PAYMENT_PAID) {
            $brioche = $this->findBrioche($ipn->getOrder());

            $brioche->setPaid($brioche->getPaid() + ($ipn->getAmount() / 100));
            $brioche->setValid(true);
        }
        
        $this->em->persist($ipn);
        $this->em->flush();
    }
    
    /**
     * @param integer $briocheId
     * 
     * @return Brioche
     */
    private function findBrioche($briocheId)
    {
        return $this->em
            ->getRepository('BriocheCoreBundle:Brioche')
            ->find($briocheId)
        ;
    }
}
