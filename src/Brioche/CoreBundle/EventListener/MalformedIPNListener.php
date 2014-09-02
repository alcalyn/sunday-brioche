<?php

namespace Brioche\CoreBundle\EventListener;

use Symfony\Bridge\Monolog\Logger;
use Alcalyn\PayplugBundle\Event\PayplugMalformedIPNEvent;

class MalformedIPNListener
{
    /**
     * @var Logger
     */
    private $logger;
    
    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * @param PayplugIPNEvent $event
     */
    public function onMalformedIPN(PayplugMalformedIPNEvent $event)
    {
        $this->logger->addAlert('Malformed IPN: '.$event->getRequest()->getContent());
    }
}
