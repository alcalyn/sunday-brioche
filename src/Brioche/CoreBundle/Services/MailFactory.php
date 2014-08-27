<?php

namespace Brioche\CoreBundle\Services;

use Swift_Mailer;
use Swift_Message;
use Swift_Mime_Message;
use Symfony\Component\Templating\EngineInterface;

class MailFactory
{
    /**
     * @var EngineInterface
     */
    private $templating;
    
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    
    /**
     * @param EngineInterface $templating
     * @param Swift_Mailer $mailer
     */
    public function __construct(EngineInterface $templating, Swift_Mailer $mailer)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
    }
    
    public function createMail($template, array $variables = array())
    {
        $mail = Swift_Message::newInstance()
            ->setSubject('Brioche du Dimanche')
            ->setFrom(array('contact@free.fr' => 'Brioche du Dimanche'))
        ;
        
        $body = $this->templating->render($template, $variables);
        
        $mail->setBody($body, 'text/html');
        
        return $mail;
    }
    
    public function createBriocheValidatedMail()
    {
        return $this->createMail('BriocheCoreBundle:Mail:test.html.twig', array(
        ));
    }
    
    public function createTestMail()
    {
        return $this->createMail('BriocheCoreBundle:Mail:test.html.twig', array(
            'txt' => 'hello',
        ));
    }
}
