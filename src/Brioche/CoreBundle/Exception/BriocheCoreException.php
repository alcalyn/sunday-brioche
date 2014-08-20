<?php

namespace Brioche\CoreBundle\Exception;

class BriocheCoreException extends BriocheException
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
