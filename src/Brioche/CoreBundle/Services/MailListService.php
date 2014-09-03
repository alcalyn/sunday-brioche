<?php

namespace Brioche\CoreBundle\Services;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactory;
use Brioche\CoreBundle\Form\Type\MailType;
use Brioche\CoreBundle\Entity\Mail;

class MailListService
{
    /**
     * @var FormFactory
     */
    private $formFactory;
    
    /**
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }
    
    /**
     * @param string $action route
     * 
     * @return FormInterface
     */
    public function createForm($action = null)
    {
        if (null === $action) {
            $mailForm = $this->formFactory->create(new MailType());
        } else {
            $mailForm = $this->formFactory->create(new MailType(), null, array(
                'action' => $action,
            ));
        }
        
        return $mailForm;
    }
    
    /**
     * @param FormInterface $mailForm
     * 
     * @return array
     */
    public function processForm(FormInterface $mailForm, $em)
    {
        if ($mailForm->isValid()) {
            $mail = $mailForm->getData();
            $mail->setDateCreate(new \DateTime());
            
            try {
                $em->persist($mail);
                $em->flush();
                
                return array(
                    'ok' => true,
                );
            } catch (\Doctrine\DBAL\DBALException $e) {
                return array(
                    'ok' => false,
                    'reason' => 'duplicate',
                );
            }
        } else {
            return array(
                'ok' => false,
                'reason' => 'form_invalid',
            );
        }
    }
}
