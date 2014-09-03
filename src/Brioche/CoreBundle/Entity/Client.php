<?php

namespace Brioche\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Client
 *
 * @ORM\Table(name="sb_client")
 * @ORM\Entity(repositoryClass="Brioche\CoreBundle\Repository\ClientRepository")
 */
class Client
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
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     * 
     * @Assert\NotBlank
     */
    private $first_name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     * 
     * @Assert\NotBlank
     */
    private $last_name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     * 
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * 
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;
    
    /**
     * @var City
     * 
     * @ORM\ManyToOne(targetEntity="Brioche\CoreBundle\Entity\City")
     * @ORM\JoinColumn(nullable=true)
     * 
     * @Assert\NotBlank
     */
    private $city;
    
    /**
     * @var Account
     * 
     * @ORM\OneToOne(targetEntity="Brioche\CoreBundle\Entity\Account")
     * @ORM\JoinColumn(nullable=true)
     */
    private $account;

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
     * Set first_name
     *
     * @param string $firstName
     * @return Client
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return Client
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }
    
    /**
     * Get full name
     * 
     * @return string
     */
    public function getFullName()
    {
        $fullName = array();
        
        if (null !== $this->getFirstName()) {
            $fullName []= $this->getFirstName();
        }
        
        if (null !== $this->getLastName()) {
            $fullName []= $this->getLastName();
        }
        
        return implode(' ', $fullName);
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Client
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param \Brioche\CoreBundle\Entity\City $city
     * @return Client
     */
    public function setCity(City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Brioche\CoreBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set account
     *
     * @param \Brioche\CoreBundle\Entity\Account $account
     * @return Client
     */
    public function setAccount(Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Brioche\CoreBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }
}
