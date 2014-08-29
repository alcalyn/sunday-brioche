<?php

namespace Brioche\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Brioche\CoreBundle\Entity\Brioche;

/**
 * Message
 *
 * @ORM\Table(name="sb_message")
 * @ORM\Entity(repositoryClass="Brioche\CoreBundle\Repository\MessageRepository")
 */
class Message
{
    const AUTHOR_CLIENT = 1;
    const AUTHOR_BRIOCHE_DU_DIMANCHE = 2;
    const AUTHOR_ROBOT = 3;
    
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
     * @ORM\Column(name="content", type="string", length=1023)
     */
    private $content;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="author", type="smallint")
     */
    private $author;
    
    /**
     * @var Brioche
     * 
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\Brioche", inversedBy="messages")
     */
    private $brioche;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();
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
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Message
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set author
     *
     * @param integer $author
     * @return Message
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return integer 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set brioche
     *
     * @param \Brioche\CoreBundle\Entity\Brioche $brioche
     * @return Message
     */
    public function setBrioche(\Brioche\CoreBundle\Entity\Brioche $brioche = null)
    {
        $this->brioche = $brioche;

        return $this;
    }

    /**
     * Get brioche
     *
     * @return \Brioche\CoreBundle\Entity\Brioche 
     */
    public function getBrioche()
    {
        return $this->brioche;
    }
}
