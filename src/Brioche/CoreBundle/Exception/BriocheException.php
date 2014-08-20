<?php

namespace Brioche\CoreBundle\Exception;

class BriocheException extends \Exception
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, 0, null);
    }
}
