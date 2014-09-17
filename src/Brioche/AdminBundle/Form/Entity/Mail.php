<?php

namespace Brioche\AdminBundle\Form\Entity;

class Mail
{
    private $to;
    
    private $content;
    
    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to)
    {
        $this->to = $to;
        
        return $this;
    }
    
    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        
        return $this;
    }
}
