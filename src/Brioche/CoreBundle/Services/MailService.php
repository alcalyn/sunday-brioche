<?php

namespace Brioche\CoreBundle\Services;

use Swift_Mailer;
use Swift_Message;

class MailService
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    
    /**
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function sendTestMail()
    {
        // Create a message
        $message = Swift_Message::newInstance('Wonderful Subject')
            ->setFrom(array('john@doe.com' => 'John Doe'))
            ->setTo(array('doubjulien@hotmail.fr' => 'Juju'))
            ->setBody('Hello, this is a test message')
        ;

        // Send the message
        $result = $this->mailer->send($message);
        
        var_dump($result);
    }
}
