<?php

namespace Brioche\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Brioche
 *
 * @ORM\Table(name="sb_brioche")
 * @ORM\Entity(repositoryClass="Brioche\CoreBundle\Repository\BriocheRepository")
 */
class Brioche
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
     * @var Client
     * 
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\Client")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;
    
    /**
     * @var Round
     * 
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\Round")
     * @ORM\JoinColumn(nullable=false)
     */
    private $round;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="size", type="smallint")
     */
    private $size;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="butter", type="smallint")
     */
    private $butter;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="sugar", type="smallint")
     */
    private $sugar;
    
    /**
     * @var Extra
     * 
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\Extra")
     * @ORM\JoinColumn(nullable=true)
     */
    private $extra;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Brioche
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Brioche
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set butter
     *
     * @param integer $butter
     * @return Brioche
     */
    public function setButter($butter)
    {
        $this->butter = $butter;

        return $this;
    }

    /**
     * Get butter
     *
     * @return integer 
     */
    public function getButter()
    {
        return $this->butter;
    }

    /**
     * Set sugar
     *
     * @param integer $sugar
     * @return Brioche
     */
    public function setSugar($sugar)
    {
        $this->sugar = $sugar;

        return $this;
    }

    /**
     * Get sugar
     *
     * @return integer 
     */
    public function getSugar()
    {
        return $this->sugar;
    }

    /**
     * Set client
     *
     * @param \Brioche\CoreBundle\Entity\Client $client
     * @return Brioche
     */
    public function setClient(\Brioche\CoreBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Brioche\CoreBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set round
     *
     * @param \Brioche\CoreBundle\Entity\Round $round
     * @return Brioche
     */
    public function setRound(\Brioche\CoreBundle\Entity\Round $round)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return \Brioche\CoreBundle\Entity\Round 
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Set extra
     *
     * @param \Brioche\CoreBundle\Entity\Extra $extra
     * @return Brioche
     */
    public function setExtra(\Brioche\CoreBundle\Entity\Extra $extra = null)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return \Brioche\CoreBundle\Entity\Extra 
     */
    public function getExtra()
    {
        return $this->extra;
    }
}
