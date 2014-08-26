<?php

namespace Brioche\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Alcalyn\PayplugBundle\Model\IPN as BaseIPN;

/**
 * IPN
 *
 * @ORM\Table(name="sb_ipn")
 * @ORM\Entity
 */
class IPN extends BaseIPN
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
