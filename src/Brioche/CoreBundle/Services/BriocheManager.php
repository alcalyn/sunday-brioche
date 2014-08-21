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
        $size = $brioche->getSize();
        $extra = $brioche->getExtra();
        $price = 0;
        
        if ($size) {
            $price += $size->getPrice();
            
            if ($extra) {
                $price += $extra->getPrice();
            }
        }
        
        return $price;
    }
}
