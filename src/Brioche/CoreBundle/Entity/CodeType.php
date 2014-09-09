<?php

namespace Brioche\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CodeType
 *
 * @ORM\Table(name="sb_code_type")
 * @ORM\Entity(repositoryClass="Brioche\CoreBundle\Repository\CodeTypeRepository")
 */
class CodeType
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
     * Effect title as the client see
     * 
     * @var string
     * 
     * @ORM\Column(name="title", type="string", length=31, nullable=true)
     */
    private $title;
    
    /**
     * Effect of this code (reduce price...)
     * Format: "price_coef:0.8"
     * 
     * @var string
     * 
     * @ORM\Column(name="effect", type="string", length=127)
     */
    private $effect;


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
     * Set title
     *
     * @param string $title
     * @return CodeType
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set effect
     *
     * @param string $effect
     * @return CodeType
     */
    public function setEffect($effect)
    {
        $this->effect = $effect;

        return $this;
    }

    /**
     * Get effect
     *
     * @return string 
     */
    public function getEffect()
    {
        return $this->effect;
    }
}
