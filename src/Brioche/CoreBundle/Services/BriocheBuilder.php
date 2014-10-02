<?php

namespace Brioche\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Brioche\CoreBundle\Exception\BriocheCoreException;
use Brioche\CoreBundle\Exception\BriocheException;
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
     * @var CodeManager
     */
    private $codeManager;
    
    /**
     * @var FlashMessages
     */
    private $flashMessages;
    
    /**
     * @param EntityManagerInterface $em
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Brioche\CoreBundle\Services\BriocheManager $briocheManager
     * @param \Brioche\CoreBundle\Services\CodeManager $codeManager
     * @param \Brioche\CoreBundle\Services\FlashMessages $flashMessages
     */
    public function __construct(
            EntityManagerInterface $em,
            Session $session,
            BriocheManager $briocheManager,
            CodeManager $codeManager,
            FlashMessages $flashMessages
    ) {
        $this->em = $em;
        $this->session = $session;
        $this->briocheManager = $briocheManager;
        $this->codeManager = $codeManager;
        $this->flashMessages = $flashMessages;
        
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
        }
        
        $this->brioche = $brioche;
        $this->checkRound();
        $this->updatePrice();
    }
    
    /**
     * @return BriocheBuilder
     */
    private function saveBrioche()
    {
        $this->em->persist($this->brioche);
        $this->em->flush();
        
        $this->session->set('briocheId', $this->brioche->getId());
        
        return $this;
    }
    
    /**
     * Push current brioche to history and create a new one
     * 
     * @return BriocheBuilder
     */
    public function saveToHistory()
    {
        $this->addToHistory($this->brioche);
        
        $brioche = $this->createDefaultBriocheForClient($this->brioche->getClient());
        
        $this->brioche = $brioche;
        
        return $this->saveBrioche();
    }
    
    /**
     * Add a brioche to history so user can create another one
     * or return to the command page of an old brioche
     * 
     * @param \Brioche\CoreBundle\Entity\Brioche $brioche
     * 
     * @return BriocheBuilder
     */
    public function addToHistory(Brioche $brioche)
    {
        if (!$brioche->getLocked()) {
            throw new BriocheCoreException('Cannot add to history a non locked brioche');
        }
        
        $history = $this->getHistory();
        $history[$brioche->getId()] = true;
        
        $this->session->set('briochesHistory', $history);
        
        return $this;
    }
    
    /**
     * Return an array of brioches id as key
     * which are already locked by the current user
     * 
     * @return array
     */
    public function getHistory()
    {
        if ($this->session->has('briochesHistory')) {
            return $this->session->get('briochesHistory');
        } else {
            return array();
        }
    }
    
    /**
     * Return array of Brioche which are in the history
     * 
     * @return Brioche[]
     */
    public function getHistoryFull()
    {
        $history = $this->em
            ->getRepository('BriocheCoreBundle:Brioche')
            ->findFullByIds(array_keys($this->getHistory()))
        ;
        
        usort($history, $this->sortHistoryCallbackBuilder());
        
        return $history;
    }
    
    /**
     * Return closure which sort an array of brioche to display in history
     * 
     * @return \Closure
     */
    private static function sortHistoryCallbackBuilder()
    {
        return function (Brioche $b0, Brioche $b1) {
            $t0 = $b0->getRound()->getDate()->getTimestamp();
            $t1 = $b1->getRound()->getDate()->getTimestamp();
            
            return $t1 - $t0;
        };
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
            ->setButter(2)
            ->setSugar(2)
            ->setSize($size)
            ->setExtra(null)
            ->setClient(new Client())
        ;
    }
    
    public function createDefaultBriocheForClient(Client $client)
    {
        return $this
            ->createDefaultBrioche()
            ->setClient($client)
        ;
    }
    
    /**
     * @param \Brioche\CoreBundle\Entity\Round $round
     * 
     * @return \Brioche\CoreBundle\Services\BriocheBuilder
     */
    public function buildRound(Round $round)
    {
        $this->checkLocked();
        
        if (!$round->isAvailable()) {
            $this->flashMessages
                ->addDanger('La tournée que vous avez choisi est maintenant indisponible.')
            ;
        } else {
            $this->brioche
                ->setRound($round)
                ->setValidRound(true)
            ;

            $this->saveBrioche();
        }
        
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
        
        $this->saveBrioche();
        
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
        
        $this->saveBrioche();
        
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
        
        $this->saveBrioche();
        
        return $this;
    }
    
    /**
     * @return BriocheBuilder
     */
    public function buildAddress()
    {
        $this->checkLocked();
        
        $this->brioche->setValidAddress(true);

        $this->saveBrioche();
        
        return $this;
    }
    
    /**
     * Valid ship times.
     * Swap min and max if they are not ascendant.
     * 
     * @return BriocheBuilder
     */
    public function buildTime()
    {
        $this->checkLocked();
        
        $brioche = $this->brioche;
        
        if ($brioche->getShipTimeMin() > $brioche->getShipTimeMax()) {
            $swap = $brioche->getShipTimeMin();
            $brioche->setShipTimeMin($brioche->getShipTimeMax());
            $brioche->setShipTimeMax($swap);
        }
        
        $brioche->setValidTime(true);
        
        $this->saveBrioche();
        
        return $this;
    }
    
    /**
     * @param string $codeValue
     * 
     * @return \Brioche\CoreBundle\Entity\Code $code
     * 
     * @throws BriocheException if impossible to apply code
     */
    public function applyCode($codeValue)
    {
        $code = $this->em->getRepository('BriocheCoreBundle:Code')->findCode($codeValue);
        
        if (null === $code) {
            throw new BriocheException('Ce code n\'existe pas.');
        }
        
        if (!$code->getMultipleUses() && ($code->getBrioches()->count() > 0)) {
            throw new BriocheException('Ce code a déjà été utilisé.');
        }
        
        if ((null !== $code->getRound()) && ($this->brioche->getRound() !== $code->getRound())) {
            throw new BriocheException('Ce code n\'est pas utilisable dans cette tournée.');
        }
        
        $this->brioche->setCode($code);
        
        $this->updatePrice();
        
        return $code;
    }
    
    /**
     * Lock current brioche and detach from builder
     * 
     * @return boolean
     */
    public function lockBrioche()
    {
        $this->checkLocked();
        
        if (!$this->brioche->isAllValid()) {
            throw new BriocheCoreException('Impossible to valid an incomplete brioche');
        }
        
        if (!$this->checkRound()) {
            return false;
        }
        
        $this->brioche
            ->setLocked(true)
            ->setDateLock(new \DateTime())
            ->setToken($this->briocheManager->generateToken())
        ;
        
        return true;
    }
    
    /**
     * Check if brioche round is still available
     * Add a flash message if not
     * 
     * @return boolean whether round is not longer available
     */
    public function checkRound()
    {
        if (null === $this->brioche->getRound()) {
            return false;
        }
        
        if (!$this->brioche->getRound()->isAvailable()) {
            $this->brioche
                ->setRound(null)
                ->setValidRound(false)
            ;
            
            $this->flashMessages
                ->addDanger('La tournée que vous avez choisi est maintenant indisponible.')
            ;
                
            return false;
        }
        
        return true;
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
        
        $code = $this->brioche->getCode();
        
        if ($code) {
            $reduction = $this->codeManager->getCodeReduction($code, $this->brioche);

            $this->brioche
                ->setPrice($price - $reduction)
                ->setCodeReduction($reduction)
            ;
        }
        
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
                'time',
                'summary',
            );
            
            $index = array_search($from, $steps);
            
            if (false !== $index) {
                $stepFrom = $index + 1;
            }
        }
        
        $i = 0;
        
        switch ($stepFrom) {
            default:
            case $i++:
                if (!$this->brioche->getValidRound()) {
                    return 'round';
                }
                
            case $i++:
                if (!$this->brioche->getValidType()) {
                    return 'type';
                }
                
            case $i++:
                if (!$this->brioche->getValidSize()) {
                    return 'size';
                }
                
            case $i++:
                if (!$this->brioche->getValidPerso()) {
                    return 'perso';
                }
                
            case $i++:
                if (!$this->brioche->getValidAddress()) {
                    return 'address';
                }
                
            case $i++:
                if (!$this->brioche->getValidTime()) {
                    return 'time';
                }
                
            case $i++:
                return 'summary';
        }
    }
}
