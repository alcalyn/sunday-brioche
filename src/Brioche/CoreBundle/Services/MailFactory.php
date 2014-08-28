<?php

namespace Brioche\CoreBundle\Services;

use Swift_Mailer;
use Swift_Message;
use Swift_Mime_Message;
use Symfony\Component\Templating\EngineInterface;
use Brioche\CoreBundle\Entity\Client;

class MailFactory
{
    /**
     * Set to false to disable mail in dev
     * 
     * @var boolean
     */
    const MAILS_ENABLED = true;
    
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
    
    /**
     * Create a standard mail
     * 
     * @param string $template
     * @param array $variables
     * @return Swift_Mime_Message
     */
    public function createMail($template, array $variables = array())
    {
        $mail = Swift_Message::newInstance()
            ->setSubject('Brioche du Dimanche')
            ->setFrom(array('contact@brioche-du-dimanche.com' => 'Brioche du Dimanche'))
        ;
        
        $body = $this->templating->render($template, $variables);
        
        $mail->setBody($body, 'text/html');
        
        return $mail;
    }
    
    /**
     * Send a mail when a brioche has been validated
     * 
     * @param \Brioche\CoreBundle\Entity\Client $client
     * @param string $commandUrl
     */
    public function sendBriocheValidatedMail(Client $client, $commandUrl)
    {
        $mail = $this->createMail('BriocheCoreBundle:Mail:briocheValidated.html.twig', array(
            'command_url' => $commandUrl,
        ));
        
        $this->setTo($mail, $client);
        
        $this->send($mail);
    }
    
    /**
     * Send a mail when a payment has been received
     * 
     * @param \Brioche\CoreBundle\Entity\Client $client
     * @param integer
     */
    public function sendPaymentReceivedMail(Client $client, $value)
    {
        $mail = $this->createMail('BriocheCoreBundle:Mail:paymentReceived.html.twig', array(
            'value' => $value,
        ));
        
        $this->setTo($mail, $client);
        
        $this->send($mail);
    }
    
    /**
     * Set to $client
     * 
     * @param Swift_Mime_Message $mail
     * @param \Brioche\CoreBundle\Entity\Client $client
     */
    public function setTo(Swift_Mime_Message $mail, Client $client)
    {
        $mail->setTo(array(
            $client->getEmail() => $client->getFullName(),
        ));
    }
    
    /**
     * @param Swift_Mime_Message $mail
     */
    public function send(Swift_Mime_Message $mail)
    {
        if (self::MAILS_ENABLED) {
            $this->mailer->send($mail);
        }
    }
}
