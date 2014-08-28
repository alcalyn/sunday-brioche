<?php

namespace Brioche\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Brioche\CoreBundle\Exception\BriocheCoreException;
use Brioche\CoreBundle\Entity\Brioche;
use Brioche\CoreBundle\Entity\Client;
use Brioche\CoreBundle\Entity\Round;
use Brioche\CoreBundle\Entity\Extra;
use Brioche\CoreBundle\Entity\Size;

class BriocheBuilder
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var Session
     */
    private $session;
    
    /**
     * @var Brioche
     */
    private $brioche;
    
    /**
     * @var BriocheManager
     */
    private $briocheManager;
    
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Brioche\CoreBundle\Services\BriocheManager $briocheManager
     */
    public function __construct(EntityManagerInterface $em, Session $session, BriocheManager $briocheManager)
    {
        $this->em = $em;
        $this->session = $session;
        $this->briocheManager = $briocheManager;
        
        $this->loadBrioche();
    }
    
    /**
     * Load brioche from session and db or create a new
     */
    private function loadBrioche()
    {
        $brioche = null;
        
        if ($this->session->has('briocheId')) {
            $brioche = $this->em
                ->getRepository('BriocheCoreBundle:Brioche')
                ->findFullById($this->session->get('briocheId'))
            ;
        }
        
        if (null === $brioche) {
            $brioche = $this->createDefaultBrioche();
            $this->em->persist($brioche);
            $this->em->flush();
            $this->session->set('briocheId', $brioche->getId());
        }
        
        $this->brioche = $brioche;
        $this->updatePrice();
    }
    
    /**
     * Get brioche that is currently building
     * 
     * @return Brioche
     */
    public function getCurrentBrioche()
    {
        return $this->brioche;
    }
    
    /**
     * @return \Brioche\CoreBundle\Entity\Brioche
     */
    public function createDefaultBrioche()
    {
        $brioche = new Brioche();
        
        $size = $this->em->getRepository('BriocheCoreBundle:Size')->findOneBy(array(
            'value' => 250,
        ));
        
        return $brioche
            ->setSize($size)
            ->setExtra(null)
            ->setClient(new Client())
        ;
    }
    
    /**
     * @param \Brioche\CoreBundle\Entity\Round $round
     * 
     * @return \Brioche\CoreBundle\Services\BriocheBuilder
     * 
     * @throws BriocheCoreException if $round is full
     */
    public function buildRound(Round $round)
    {
        $this->checkLocked();
        
        if ($round->isFull()) {
            throw new BriocheCoreException('This round is full');
        }
        
        $this->brioche
            ->setRound($round)
            ->setValidRound(true)
        ;
        
        return $this;
    }
    
    /**
     * @param int $type
     * 
     * @return \Brioche\CoreBundle\Services\BriocheBuilder
     * 
     * @throws BriocheCoreException if $type is invalid
     */
    public function buildType($type)
    {
        $this->checkLocked();
        
        if (!$this->briocheManager->checkType($type)) {
            throw new BriocheCoreException('unexpected value of $type');
        }

        $this->brioche
            ->setType($type)
            ->setValidType(true)
        ;
        
        return $this;
    }
    
    /**
     * @param \Brioche\CoreBundle\Entity\Size $size
     * 
     * @return \Brioche\CoreBundle\Services\BriocheBuilder
     */
    public function buildSize(Size $size)
    {
        $this->checkLocked();
        
        $this->brioche
            ->setSize($size)
            ->setValidSize(true)
        ;
        
        $this->updatePrice();
        
        return $this;
    }
    
    /**
     * @param int $butter
     * @param int $sugar
     * @param \Brioche\CoreBundle\Entity\Extra $extra
     * 
     * @return \Brioche\CoreBundle\Services\BriocheBuilder
     */
    public function buildPerso($butter, $sugar, Extra $extra)
    {
        $this->checkLocked();
        
        $this->brioche
            ->setButter($butter)
            ->setSugar($sugar)
            ->setExtra($extra)
            ->setValidPerso(true)
        ;
        
        $this->updatePrice();
        
        return $this;
    }
    
    /**
     * Lock current brioche and detach from builder
     */
    public function lockBrioche()
    {
        if (!$this->brioche->isAllValid()) {
            throw new BriocheCoreException('Impossible to valid an incomplete brioche');
        }
        
        if ($this->brioche->getLocked()) {
            throw new BriocheCoreException('Brioche already locked');
        }
        
        $this->brioche->setLocked(true);
        $this->brioche->setToken($this->briocheManager->generateToken());
    }
    
    /**
     * @throws BriocheCoreException if brioche is locked
     */
    public function checkLocked()
    {
        if ($this->brioche->getLocked()) {
            throw new BriocheCoreException('Brioche is locked');
        }
    }
    
    /**
     * Update brioche price
     * 
     * @return \Brioche\CoreBundle\Services\BriocheBuilder
     */
    private function updatePrice()
    {
        $price = $this->briocheManager->calculatePrice($this->brioche);
        $this->brioche->setPrice($price);
        
        return $this;
    }
    
    /**
     * Return next step which is not yet done from the beginning
     * 
     * @param string $from a step
     * 
     * @return string
     */
    public function getNextStep($from = null)
    {
        $stepFrom = 0;
        
        if (null !== $from) {
            $steps = array(
                'round',
                'type',
                'size',
                'perso',
                'address',
                'summary',
            );
            
            $index = array_search($from, $steps);
            
            if ($index) {
                $stepFrom = $index + 1;
            }
        }
        
        switch ($stepFrom) {
            default:
            case 0:
                if (!$this->brioche->getValidRound()) {
                    return 'round';
                }
                
            case 1:
                if (!$this->brioche->getValidType()) {
                    return 'type';
                }
                
            case 2:
                if (!$this->brioche->getValidSize()) {
                    return 'size';
                }
                
            case 3:
                if (!$this->brioche->getValidPerso()) {
                    return 'perso';
                }
                
            case 4:
                if (!$this->brioche->getValidAddress()) {
                    return 'address';
                }
                
            case 5:
                return 'summary';
        }
    }
}
