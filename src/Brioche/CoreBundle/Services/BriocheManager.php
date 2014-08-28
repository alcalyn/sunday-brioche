<?php

namespace Brioche\CoreBundle\Services;

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
    public function calculatePrice(Brioche $brioche)
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
    
    /**
     * Check if type is valid
     * 
     * @param int $type
     * 
     * @return boolean
     */
    public function checkType($type)
    {
        return in_array($type, array(
            Brioche::VENDEENE,
            Brioche::PARISIENNE,
        ));
    }
    
    /**
     * @return string
     */
    public function generateToken()
    {
        return substr(str_shuffle(md5(uniqid('', true))), 1);
    }
}
