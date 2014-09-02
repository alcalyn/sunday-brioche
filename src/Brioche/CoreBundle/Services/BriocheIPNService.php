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
     * @var MailFactory
     */
    private $mailer;
    
    /**
     * @param EntityManagerInterface $em
     * @param MailFactory $mailer
     */
    public function __construct(EntityManagerInterface $em, MailFactory $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }
    
    /**
     * @param IPN $ipn
     */
    public function processIPN(IPN $ipn)
    {
        if ($ipn->getState() === IPN::PAYMENT_PAID) {
            // Update brioche if payment is sucess
            $brioche = $this->findBrioche($ipn->getOrder());

            $brioche->setPaid($brioche->getPaid() + ($ipn->getAmount() / 100));
            
            if ($ipn->getAmount() > 0) {
                $brioche
                    ->setValidated(true)
                    ->setDateValidate(new \DateTime())
                ;
            }
            
            $this->mailer->sendPaymentReceivedMail($brioche->getClient(), $ipn->getAmount() / 100);
        }
        
        // Save IPN in database in every case
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
