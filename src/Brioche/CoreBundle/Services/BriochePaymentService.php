<?php

namespace Brioche\CoreBundle\Services;

use Alcalyn\PayplugBundle\Model\Payment;
use Alcalyn\PayplugBundle\Services\PayplugPaymentService;
use Brioche\CoreBundle\Exception\BriocheException;
use Brioche\CoreBundle\Entity\Brioche;

class BriochePaymentService
{
    /**
     * @var PayplugPaymentService
     */
    private $payplugPayment;
    
    /**
     * @param PayplugPaymentService $payplugPayment
     */
    public function __construct(PayplugPaymentService $payplugPayment)
    {
        $this->payplugPayment = $payplugPayment;
    }
    
    /**
     * @param Brioche $brioche
     * @param string $returnUrl
     * @param boolean $half if true, the price will be only the half
     * 
     * @return string
     * 
     * @throws BriocheException if cannot process to payment now
     */
    public function getPaymentUrl(Brioche $brioche, $returnUrl, $half = false)
    {
        if (!$brioche->isAllValid()) {
            throw new BriocheException('Cannot pay brioche now, not completely finnished');
        }
        
        if (!$brioche->getLocked()) {
            throw new BriocheException('Cannot pay brioche now, it is not validated');
        }
        
        if ($brioche->getValidated()) {
            throw new BriocheException('Cannot pay brioche, this is already paid');
        }
        
        $client = $brioche->getClient();
        $payment = new Payment();
        
        $payment
            ->setCurrency(Payment::EUROS)
            ->setReturnUrl($returnUrl)
            ->setCancelUrl($returnUrl)
            ->setCustomData('Custom data Brioche du Dimanche')
            ->setAmount($brioche->getPrice() * ($half ? 50 : 100))
            ->setFirstName($client->getFirstName())
            ->setLastName($client->getLastName())
            ->setEmail($client->getEmail())
            ->setCustomer($client->getId())
            ->setOrder($brioche->getId())
            ->setOrigin('Brioche du Dimanche')
        ;
        
        return $this->payplugPayment->generateUrl($payment);
    }
}
