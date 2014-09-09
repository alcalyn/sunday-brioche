<?php

namespace Brioche\CoreBundle\Services;

use Brioche\CoreBundle\Exception\BriocheCoreException;
use Brioche\CoreBundle\Entity\Code;
use Brioche\CoreBundle\Entity\Brioche;

class CodeManager
{
    /**
     * Get all posible characters in a code,
     * only upper case and numbers, without similar characters,
     * as: Z2 S5 0OQ I1
     * 
     * @return string
     */
    public function getPossibleCharacters()
    {
        return 'ABCDEFGHJKLMNPRTUVWXY346789';
    }
    
    /**
     * @param int $length
     * @return string
     */
    public function generateCode($length = 5)
    {
        $chars = $this->getPossibleCharacters();
        $randMax = strlen($chars) - 1;
        
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[rand(0, $randMax)];
        }
        
        return $code;
    }
    
    /**
     * Get price reduction for a code on a brioche
     * 
     * @param Code $code
     * @param Brioche $brioche
     * 
     * @return float price reduction
     */
    public function getCodeReduction(Code $code, Brioche $brioche)
    {
        $arg = explode(':', $code->getCodeType()->getEffect());
            
        switch ($name = $arg[0]) {

            case 'price_coef':
                $reduction = 1 - floatval($arg[1]);
                return $brioche->getPrice() * $reduction;

            default:
                throw new BriocheCoreException('Unexpected effect name: "'.$name.'"');
        }
    }
}
