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
     * @var int
     */
    const VENDEENE = 0;
    
    /**
     * @var int
     */
    const PARISIENNE = 1;
    
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
     * @ORM\JoinColumn(nullable=true)
     */
    private $client;
    
    /**
     * @var Round
     * 
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\Round")
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\Size")
     * @ORM\JoinColumn(nullable=true)
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
     * @var float
     * 
     * @ORM\Column(name="price", type="decimal", precision=4, scale=2)
     */
    private $price;
    
    /**
     * @var float
     * 
     * @ORM\Column(name="paid", type="decimal", precision=4, scale=2)
     */
    private $paid;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="valid", type="boolean")
     */
    private $valid;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateCreate", type="datetime")
     */
    private $dateCreate;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this
            ->setType(Brioche::VENDEENE)
            ->setButter(1)
            ->setSugar(1)
            ->setPrice(0)
            ->setPaid(0)
            ->setValid(false)
            ->setDateCreate(new \DateTime())
        ;
    }
    
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
     * @param \Brioche\CoreBundle\Entity\Size $size
     * @return Brioche
     */
    public function setSize(Size $size = null)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return \Brioche\CoreBundle\Entity\Size 
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
    public function setClient(Client $client = null)
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
    public function setRound(Round $round = null)
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
    public function setExtra(Extra $extra = null)
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

    /**
     * Set price
     *
     * @param string $price
     * @return Brioche
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set paid
     *
     * @param string $paid
     * @return Brioche
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return string 
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     * @return Brioche
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return boolean 
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Brioche
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }
}
