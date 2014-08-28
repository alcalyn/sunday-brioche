<?php

namespace Brioche\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Round
 *
 * @ORM\Table(name="sb_round")
 * @ORM\Entity(repositoryClass="Brioche\CoreBundle\Repository\RoundRepository")
 */
class Round
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
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Brioche\CoreBundle\Entity\Brioche", mappedBy="round")
     */
    private $brioches;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="maximum", type="smallint")
     */
    private $maximum;

    /**
     * @var integer
     *
     * @ORM\Column(name="dummy", type="smallint")
     */
    private $dummy;

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
     * Set date
     *
     * @param \DateTime $date
     * @return Round
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set maximum
     *
     * @param integer $maximum
     * @return Round
     */
    public function setMaximum($maximum)
    {
        $this->maximum = $maximum;

        return $this;
    }

    /**
     * Get maximum
     *
     * @return integer 
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * Set dummy
     *
     * @param integer $dummy
     * @return Round
     */
    public function setDummy($dummy)
    {
        $this->dummy = $dummy;

        return $this;
    }

    /**
     * Get dummy
     *
     * @return integer 
     */
    public function getDummy()
    {
        return $this->dummy;
    }
    
    /**
     * Get total of orders counting real orders plus dummies orders
     * 
     * @return integer
     */
    public function getTotal()
    {
        return $this->dummy + $this->getBrioches()->count();
    }
    
    /**
     * Return whether round is full or orders
     * 
     * @return boolean
     */
    public function isFull()
    {
        return $this->getTotal() >= $this->getMaximum();
    }

    /**
     * Add brioches
     *
     * @param \Brioche\CoreBundle\Entity\Brioche $brioches
     * @return Round
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
}
