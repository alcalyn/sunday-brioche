<?php

namespace Brioche\CoreBundle\EventListener;

use Alcalyn\PayplugBundle\Event\PayplugIPNEvent;
use Brioche\CoreBundle\Services\BriocheIPNService;

class PaymentListener
{
    /**
     * @var BriocheIPNService
     */
    private $briocheIPNService;
    
    /**
     * @param BriocheIPNService $briocheIPNService
     */
    public function __construct(BriocheIPNService $briocheIPNService)
    {
        $this->briocheIPNService = $briocheIPNService;
    }
    
    /**
     * @param PayplugIPNEvent $event
     */
    public function onPayment(PayplugIPNEvent $event)
    {
        $this->briocheIPNService->processIPN($event->getIPN());
    }
}
