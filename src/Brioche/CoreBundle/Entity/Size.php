<?php

namespace Brioche\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Size
 *
 * @ORM\Table(name="sb_size")
 * @ORM\Entity(repositoryClass="Brioche\CoreBundle\Repository\SizeRepository")
 */
class Size
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
     * @var integer
     *
     * @ORM\Column(name="value", type="smallint")
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=4, scale=2)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPeople", type="smallint")
     */
    private $nbPeople;

    /**
     * @var integer
     *
     * @ORM\Column(name="averageWeight", type="smallint")
     */
    private $averageWeight;


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
     * Set value
     *
     * @param integer $value
     * @return Size
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Size
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
     * Set nbPeople
     *
     * @param integer $nbPeople
     * @return Size
     */
    public function setNbPeople($nbPeople)
    {
        $this->nbPeople = $nbPeople;

        return $this;
    }

    /**
     * Get nbPeople
     *
     * @return integer 
     */
    public function getNbPeople()
    {
        return $this->nbPeople;
    }

    /**
     * Set averageWeight
     *
     * @param integer $averageWeight
     * @return Size
     */
    public function setAverageWeight($averageWeight)
    {
        $this->averageWeight = $averageWeight;

        return $this;
    }

    /**
     * Get averageWeight
     *
     * @return integer 
     */
    public function getAverageWeight()
    {
        return $this->averageWeight;
    }
}
