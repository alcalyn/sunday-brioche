<?php

namespace Brioche\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

class FlashMessages
{
    /**
     * @var Session
     */
    private $session;
    
    /**
     * Constructor
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    
    /**
     * Add a flash message
     * 
     * @param string $message
     * @param string $type
     */
    public function add($message, $type)
    {
        $this->session->getFlashBag()->add($type, $message);
    }
    
    /**
     * Add a success message
     * 
     * @param string $message
     */
    public function addSuccess($message)
    {
        $this->add($message, 'success');
    }
    
    /**
     * Add an info message
     * 
     * @param string $message
     */
    public function addInfo($message)
    {
        $this->add($message, 'info');
    }
    
    /**
     * Add a warning message
     * 
     * @param string $message
     */
    public function addWarning($message)
    {
        $this->add($message, 'warning');
    }
    
    /**
     * Add a danger message
     * 
     * @param string $message
     */
    public function addDanger($message)
    {
        $this->add($message, 'danger');
    }
}
