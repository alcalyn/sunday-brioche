<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FrontController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $client = new \Brioche\CoreBundle\Entity\Client;
        
        $em = $this->getDoctrine()->getManager();
        
        $client->setName('Rosette Dupuis');
        $client->setAddress('1, rue de par là');
        $client->setCity();
        
        $em->persist($client);
        $em->flush();
        
        return array();
    }
}
