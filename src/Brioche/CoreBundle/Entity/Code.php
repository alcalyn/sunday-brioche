<?php

namespace Brioche\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Code
 *
 * @ORM\Table(name="sb_code")
 * @ORM\Entity(repositoryClass="Brioche\CoreBundle\Repository\CodeRepository")
 */
class Code
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=31, unique=true)
     */
    private $code;
    
    /**
     * Type of code
     * 
     * @var CodeType
     * 
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\CodeType")
     * @ORM\JoinColumn(nullable=true)
     */
    private $codeType;
    
    /**
     * Client which got this code
     * 
     * @var string
     * 
     * @ORM\Column(name="owner", type="string", length=31, nullable=true)
     */
    private $owner;
    
    /**
     * Brioches which use this code
     * 
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Brioche\CoreBundle\Entity\Brioche", mappedBy="code")
     */
    private $brioches;
    
    /**
     * Whether we can use this code many times
     * 
     * @var boolean
     * 
     * @ORM\Column(name="multiple_uses", type="boolean")
     */
    private $multipleUses;
    
    /**
     * Restrict this code to a round
     * 
     * @var Round
     * 
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\Round")
     * @ORM\JoinColumn(nullable=true)
     */
    private $round;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->brioches = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set code
     *
     * @param string $code
     * @return Code
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set owner
     *
     * @param string $owner
     * @return Code
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set multipleUses
     *
     * @param boolean $multipleUses
     * @return Code
     */
    public function setMultipleUses($multipleUses)
    {
        $this->multipleUses = $multipleUses;

        return $this;
    }

    /**
     * Get multipleUses
     *
     * @return boolean 
     */
    public function getMultipleUses()
    {
        return $this->multipleUses;
    }

    /**
     * Set codeType
     *
     * @param \Brioche\CoreBundle\Entity\CodeType $codeType
     * @return Code
     */
    public function setCodeType(\Brioche\CoreBundle\Entity\CodeType $codeType = null)
    {
        $this->codeType = $codeType;

        return $this;
    }

    /**
     * Get codeType
     *
     * @return \Brioche\CoreBundle\Entity\CodeType 
     */
    public function getCodeType()
    {
        return $this->codeType;
    }

    /**
     * Add brioches
     *
     * @param \Brioche\CoreBundle\Entity\Brioche $brioches
     * @return Code
     */
    public function addBrioche(Brioche $brioches)
    {
        $this->brioches[] = $brioches;

        return $this;
    }

    /**
     * Remove brioches
     *
     * @param \Brioche\CoreBundle\Entity\Brioche $brioches
     */
    public function removeBrioche(Brioche $brioches)
    {
        $this->brioches->removeElement($brioches);
    }

    /**
     * Get brioches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBrioches()
    {
        return $this->brioches;
    }

    /**
     * Set round
     *
     * @param \Brioche\CoreBundle\Entity\Round $round
     * @return Code
     */
    public function setRound(\Brioche\CoreBundle\Entity\Round $round = null)
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
}
