<?php

namespace Brioche\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
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
        
        $this->brioche = $this->loadBrioche();
    }
    
    /**
     * Load brioche from session and db or create a new
     * 
     * @return \Brioche\CoreBundle\Entity\Brioche
     */
    private function loadBrioche()
    {
        if ($this->session->has('briocheId')) {
            $brioche = $this->em
                ->getRepository('BriocheCoreBundle:Brioche')
                ->find($this->session->get('briocheId'))
            ;
        }
        
        if (null === $brioche) {
            $brioche = $this->createDefaultBrioche();
            $this->em->persist($brioche);
            $this->em->flush();
            $this->session->set('briocheId', $brioche->getId());
        }
        
        return $brioche;
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
     * Valid current brioche and detach from builder
     */
    public function validCurrentBrioche()
    {
        $this->brioche->setValid(true);
        $this->session->remove('briocheId');
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
        
        $extra = $this->em->getRepository('BriocheCoreBundle:Extra')->findOneBy(array(
            'title' => 'Nature',
        ));
        
        return $brioche
            ->setSize($size)
            ->setExtra($extra)
            ->setClient(new Client())
        ;
    }
    
    public function buildRound(Round $round)
    {
        $this->brioche
            ->setRound($round)
            ->setValidRound(true)
        ;
        
        return $this;
    }
    
    public function buildType($type)
    {
        if (!$this->briocheManager->checkType($type)) {
            throw new BriocheCoreException('unexpected value of $type');
        }

        $this->brioche
            ->setType($type)
            ->setValidType(true)
        ;
        
        return $this;
    }
    
    public function buildSize(Size $size)
    {
        $this->brioche
            ->setSize($size)
            ->setValidSize(true)
        ;
        
        return $this;
    }
    
    public function buildPerso($butter, $sugar, Extra $extra)
    {
        $this->brioche
            ->setButter($butter)
            ->setSugar($sugar)
            ->setExtra($extra)
            ->setValidPerso(true)
        ;
        
        return $this;
    }
}
