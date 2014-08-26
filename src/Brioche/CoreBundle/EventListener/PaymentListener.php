<?php

namespace Brioche\CoreBundle\EventListener;

use Alcalyn\PayplugBundle\Model\IPN;
use Alcalyn\PayplugBundle\Event\PayplugIPNEvent;

class PaymentListener
{
    public function onPayment(PayplugIPNEvent $event)
    {
        $ipn = $event->getIPN();
    }
}
