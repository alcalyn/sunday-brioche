<?php

namespace Brioche\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

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
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\Client", cascade={"all"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $client;
    
    /**
     * @var Round
     * 
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\Round", inversedBy="brioches")
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
     * @var string
     * 
     * @ORM\Column(name="ship_time_min", type="time")
     */
    private $shipTimeMin;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="ship_time_max", type="time")
     */
    private $shipTimeMax;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="validRound", type="boolean")
     */
    private $validRound;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="validType", type="boolean")
     */
    private $validType;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="validSize", type="boolean")
     */
    private $validSize;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="validPerso", type="boolean")
     */
    private $validPerso;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="validAddress", type="boolean")
     */
    private $validAddress;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="validTime", type="boolean")
     */
    private $validTime;
    
    /**
     * @var boolean whether brioche is validated and need to be paid
     * 
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked;
    
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
     * @var string
     * 
     * @ORM\Column(name="token", type="string", length=31, nullable=true)
     */
    private $token;
    
    /**
     * @var boolean whether brioche will be made
     * 
     * @ORM\Column(name="validated", type="boolean")
     */
    private $validated;
    
    /**
     * @var Collection
     * 
     * @ORM\OneToMany(targetEntity="Brioche\CoreBundle\Entity\Message", mappedBy="brioche")
     */
    private $messages;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateCreate", type="datetime")
     */
    private $dateCreate;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateLock", type="datetime", nullable=true)
     */
    private $dateLock;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateValidate", type="datetime", nullable=true)
     */
    private $dateValidate;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this
            ->setType(Brioche::VENDEENE)
            ->setButter(1)
            ->setSugar(1)
            ->setShipTimeMin(new \DateTime('8:00'))
            ->setShipTimeMax(new \DateTime('9:00'))
            ->setValidRound(false)
            ->setValidType(false)
            ->setValidSize(false)
            ->setValidPerso(false)
            ->setValidAddress(false)
            ->setValidTime(false)
            ->setLocked(false)
            ->setPrice(0)
            ->setPaid(0)
            ->setValidated(false)
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
     * @return string
     */
    public function getTypeTitle()
    {
        switch ($this->getType()) {
            case self::VENDEENE:
                return 'Vendéenne';
                
            case self::PARISIENNE:
                return 'Parisienne';
            
            default:
                return '???';
        }
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
     * Set shipTimeMin
     *
     * @param \DateTime $shipTimeMin
     * @return Brioche
     */
    public function setShipTimeMin($shipTimeMin)
    {
        $this->shipTimeMin = $shipTimeMin;

        return $this;
    }

    /**
     * Get shipTimeMin
     *
     * @return \DateTime 
     */
    public function getShipTimeMin()
    {
        return $this->shipTimeMin;
    }

    /**
     * Set shipTimeMax
     *
     * @param \DateTime $shipTimeMax
     * @return Brioche
     */
    public function setShipTimeMax($shipTimeMax)
    {
        $this->shipTimeMax = $shipTimeMax;

        return $this;
    }

    /**
     * Get shipTimeMax
     *
     * @return \DateTime 
     */
    public function getShipTimeMax()
    {
        return $this->shipTimeMax;
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
     * Set validRound
     *
     * @param boolean $validRound
     * @return Brioche
     */
    public function setValidRound($validRound)
    {
        $this->validRound = $validRound;

        return $this;
    }

    /**
     * Get validRound
     *
     * @return boolean 
     */
    public function getValidRound()
    {
        return $this->validRound;
    }

    /**
     * Set validType
     *
     * @param boolean $validType
     * @return Brioche
     */
    public function setValidType($validType)
    {
        $this->validType = $validType;

        return $this;
    }

    /**
     * Get validType
     *
     * @return boolean 
     */
    public function getValidType()
    {
        return $this->validType;
    }

    /**
     * Set validSize
     *
     * @param boolean $validSize
     * @return Brioche
     */
    public function setValidSize($validSize)
    {
        $this->validSize = $validSize;

        return $this;
    }

    /**
     * Get validSize
     *
     * @return boolean 
     */
    public function getValidSize()
    {
        return $this->validSize;
    }

    /**
     * Set validPerso
     *
     * @param boolean $validPerso
     * @return Brioche
     */
    public function setValidPerso($validPerso)
    {
        $this->validPerso = $validPerso;

        return $this;
    }

    /**
     * Get validPerso
     *
     * @return boolean 
     */
    public function getValidPerso()
    {
        return $this->validPerso;
    }

    /**
     * Set validAddress
     *
     * @param boolean $validAddress
     * @return Brioche
     */
    public function setValidAddress($validAddress)
    {
        $this->validAddress = $validAddress;

        return $this;
    }

    /**
     * Get validAddress
     *
     * @return boolean 
     */
    public function getValidAddress()
    {
        return $this->validAddress;
    }

    /**
     * Set validTime
     *
     * @param boolean $validTime
     * @return Brioche
     */
    public function setValidTime($validTime)
    {
        $this->validTime = $validTime;

        return $this;
    }

    /**
     * Get validTime
     *
     * @return boolean 
     */
    public function getValidTime()
    {
        return $this->validTime;
    }
    
    /**
     * Return true if all steps are valid and can process to checkout
     * 
     * @return boolean
     */
    public function isAllValid()
    {
        return
            $this->getValidRound() &&
            $this->getValidType() &&
            $this->getValidSize() &&
            $this->getValidPerso() &&
            $this->getValidAddress() &&
            $this->getValidTime()
        ;
    }

    /**
     * Set validated
     *
     * @param boolean $validated
     * @return Brioche
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean 
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return Brioche
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean 
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Brioche
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Add messages
     *
     * @param \Brioche\CoreBundle\Entity\Message $messages
     * @return Brioche
     */
    public function addMessage(Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Brioche\CoreBundle\Entity\Message $messages
     */
    public function removeMessage(Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
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

    /**
     * Set dateLock
     *
     * @param \DateTime $dateLock
     * @return Brioche
     */
    public function setDateLock($dateLock)
    {
        $this->dateLock = $dateLock;

        return $this;
    }

    /**
     * Get dateLock
     *
     * @return \DateTime 
     */
    public function getDateLock()
    {
        return $this->dateLock;
    }

    /**
     * Set dateValidate
     *
     * @param \DateTime $dateValidate
     * @return Brioche
     */
    public function setDateValidate($dateValidate)
    {
        $this->dateValidate = $dateValidate;

        return $this;
    }

    /**
     * Get dateValidate
     *
     * @return \DateTime 
     */
    public function getDateValidate()
    {
        return $this->dateValidate;
    }
}
