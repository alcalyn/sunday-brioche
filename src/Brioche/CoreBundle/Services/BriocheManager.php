<?php

namespace Brioche\CoreBundle\Services;

use Brioche\CoreBundle\Exception\BriocheCoreException;
use Brioche\CoreBundle\Entity\Brioche;

class BriocheManager
{
    /**
     * Evaluate price of a brioche
     * 
     * @param \Brioche\CoreBundle\Entity\Brioche $brioche
     * 
     * @return int
     */
    public function getPrice(Brioche $brioche)
    {
        $this->checkSize($brioche->getSize());
        
        $prices = $this->getSizePrice();
        $agrementPrice = $this->getExtraPrice();
        
        $price = $prices[$brioche->getSize()];
        
        if (null !== $brioche->getExtra()) {
            $price += $agrementPrice;
        }
        
        return $price;
    }
    
    /**
     * Check if brioche size is one of expected sizes
     * 
     * @param \Brioche\CoreBundle\Entity\Brioche $brioche
     * 
     * @throws BriocheCoreException
     */
    public function checkSize(Brioche $brioche)
    {
        $sizes = array_keys($this->getSizePrice());
        
        if (!in_array($brioche->getSize(), $sizes)) {
            throw new BriocheCoreException(
                    'Unexpected brioche size: "'.$brioche->getSize().'", expected '.implode(', or ', $sizes).'.'
            );
        }
    }
    
    /**
     * Get size/price grid
     * 
     * @return array
     */
    public function getSizePrice()
    {
        return array(
            125 => 6,
            250 => 8,
            375 => 10,
            500 => 12,
        );
    }
    
    /**
     * Get extra price
     * 
     * @return int
     */
    public function getExtraPrice()
    {
        return 2;
    }
}
